<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderTrackingController extends Controller
{
    /** Public order-tracking page (no auth required). */
    public function track(Request $request, ?string $ticket = null)
    {
        $order        = null;
        $orders       = collect();
        $searchTicket = $ticket ?? $request->query('ticket');

        if ($searchTicket) {
            $term = trim($searchTicket);

            // Try exact ticket number match first
            $order = Order::with(['items', 'customer'])
                ->where('ticket_number', strtoupper($term))
                ->first();

            // If no exact match, search by phone number or customer name
            if (!$order) {
                $orders = Order::with(['items', 'customer'])
                    ->whereHas('customer', function ($q) use ($term) {
                        $q->where('phone', $term)
                          ->orWhere('name', 'like', '%' . $term . '%');
                    })
                    ->latest()
                    ->limit(10)
                    ->get();

                // If only one result, show it directly
                if ($orders->count() === 1) {
                    $order  = $orders->first();
                    $orders = collect();
                }
            }
        }

        return view('customer.track', compact('order', 'orders', 'searchTicket'));
    }
}
