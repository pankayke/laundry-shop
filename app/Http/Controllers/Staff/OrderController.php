<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Jobs\SendOrderReadyNotification;
use App\Models\Order;
use App\Models\Setting;
use App\Models\User;
use App\Services\TicketNumberService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        private TicketNumberService $ticketService,
    ) {}

    public function create()
    {
        $settings  = Setting::instance();
        $customers = User::where('role', 'customer')->orderBy('name')->get();

        return view('staff.orders.create', compact('settings', 'customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id'          => ['required', 'exists:users,id'],
            'items'                => ['required', 'array', 'min:1'],
            'items.*.cloth_type'   => ['required', 'string', 'max:255'],
            'items.*.weight'       => ['required', 'numeric', 'min:0.1'],
            'items.*.service_type' => ['required', 'in:wash,dry,fold'],
            'notes'                => ['nullable', 'string', 'max:1000'],
        ]);

        $settings       = Setting::instance();
        $processedItems = [];
        $totalWeight    = 0;
        $totalPrice     = 0;

        foreach ($validated['items'] as $item) {
            $pricePerKg = $settings->getPriceForService($item['service_type']);
            $subtotal   = round($pricePerKg * $item['weight'], 2);

            $processedItems[] = [
                'cloth_type'   => $item['cloth_type'],
                'weight'       => $item['weight'],
                'service_type' => $item['service_type'],
                'price_per_kg' => $pricePerKg,
                'subtotal'     => $subtotal,
            ];

            $totalWeight += $item['weight'];
            $totalPrice  += $subtotal;
        }

        $order = Order::create([
            'ticket_number' => $this->ticketService->generate(),
            'customer_id'   => $validated['customer_id'],
            'staff_id'      => $request->user()->id,
            'status'        => 'received',
            'total_weight'  => $totalWeight,
            'total_price'   => $totalPrice,
            'notes'         => $validated['notes'] ?? null,
        ]);

        foreach ($processedItems as $item) {
            $order->items()->create($item);
        }

        return redirect()->route('staff.dashboard')
            ->with('success', "Order {$order->ticket_number} created successfully!");
    }

    public function edit(Order $order)
    {
        $order->load('items', 'customer');
        $settings = Setting::instance();

        return view('staff.orders.edit', compact('order', 'settings'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:' . implode(',', array_keys(Order::STATUSES))],
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $validated['status']]);

        // Dispatch notification when order becomes ready for pickup.
        if ($validated['status'] === 'ready_for_pickup' && $oldStatus !== 'ready_for_pickup') {
            SendOrderReadyNotification::dispatch($order);
        }

        return back()->with('success', "Order status updated to {$order->status_label}.");
    }

    public function updatePayment(Request $request, Order $order)
    {
        $validated = $request->validate([
            'payment_method' => ['required', 'in:cash,gcash,maya'],
            'amount_paid'    => ['required', 'numeric', 'min:' . $order->total_price],
        ]);

        $changeAmount = round($validated['amount_paid'] - $order->total_price, 2);

        $order->update([
            'payment_status' => 'paid',
            'payment_method' => $validated['payment_method'],
            'amount_paid'    => $validated['amount_paid'],
            'change_amount'  => max(0, $changeAmount),
        ]);

        return back()->with('success', 'Payment recorded successfully.');
    }

    public function search(Request $request)
    {
        $query = Order::with('customer');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                    ->orWhereHas('customer', fn ($cq) => $cq
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%"));
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $orders = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        return view('staff.orders.search', compact('orders'));
    }

    /** Create a new order pre-filled with items from a previous order. */
    public function repeat(Order $order)
    {
        $order->load('items', 'customer');
        $settings  = Setting::instance();
        $customers = User::where('role', 'customer')->orderBy('name')->get();

        return view('staff.orders.create', [
            'settings'    => $settings,
            'customers'   => $customers,
            'repeatOrder' => $order,
        ]);
    }
}
