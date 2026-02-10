<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->startOfDay();

        $receivedToday = Order::whereDate('created_at', $today)
            ->where('status', 'received')
            ->with('customer')
            ->orderByDesc('created_at')
            ->get();

        $pendingOrders = Order::whereIn('status', ['washing', 'drying', 'folding'])
            ->with('customer')
            ->orderByDesc('created_at')
            ->get();

        $readyForPickup = Order::where('status', 'ready_for_pickup')
            ->with('customer')
            ->orderByDesc('created_at')
            ->get();

        $todayStats = [
            'total_orders'  => Order::whereDate('created_at', $today)->count(),
            'total_revenue' => Order::whereDate('created_at', $today)
                ->where('payment_status', 'paid')->sum('total_price'),
            'pending_count' => $pendingOrders->count(),
            'ready_count'   => $readyForPickup->count(),
        ];

        return view('staff.dashboard', compact(
            'receivedToday',
            'pendingOrders',
            'readyForPickup',
            'todayStats',
        ));
    }
}
