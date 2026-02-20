<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;
use App\Services\TicketNumberService;
use Illuminate\Http\Request;

class OrderRequestController extends Controller
{
    public function __construct(
        private TicketNumberService $ticketService,
    ) {}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'estimated_weight'     => ['required', 'numeric', 'min:0.5', 'max:100'],
            'services'             => ['required', 'array', 'min:1'],
            'services.*'           => ['in:wash,dry,fold'],
            'special_instructions' => ['nullable', 'string', 'max:1000'],
            'payment_method'       => ['required', 'in:cash,gcash,maya'],
            'payment_reference'    => ['nullable', 'required_if:payment_method,gcash,maya', 'string', 'min:6', 'max:50'],
        ]);

        $settings   = Setting::instance();
        $services   = $validated['services'];
        $weight     = (float) $validated['estimated_weight'];
        $payMethod  = $validated['payment_method'];

        // Calculate estimated price based on selected services
        $estimatedPrice = 0;
        foreach ($services as $service) {
            $estimatedPrice += $settings->getPriceForService($service) * $weight;
        }

        $order = Order::create([
            'ticket_number'        => $this->ticketService->generate(),
            'customer_id'          => $request->user()->id,
            'status'               => 'pending_approval',
            'estimated_weight'     => $weight,
            'total_weight'         => $weight,
            'total_price'          => round($estimatedPrice, 2),
            'requested_services'   => $services,
            'special_instructions' => $validated['special_instructions'] ?? null,
            'payment_reference'    => $validated['payment_reference'] ?? null,
            'payment_method'       => $payMethod,
            'notes'                => $validated['special_instructions'] ?? null,
        ]);

        return redirect()->route('customer.dashboard')
            ->with('success', "Laundry request {$order->ticket_number} submitted! Please wait for staff approval.");
    }
}
