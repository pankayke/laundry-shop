<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->startOfDay();

        // Single query: fetch all active (non-collected, non-cancelled) orders + today's received
        $activeOrders = Order::with('customer')
            ->where(function ($q) use ($today) {
                $q->whereNotIn('status', ['collected', 'cancelled'])
                  ->orWhere(function ($q2) use ($today) {
                      $q2->where('status', 'received')
                          ->whereDate('created_at', $today);
                  });
            })
            ->orderByDesc('created_at')
            ->get();

        // Partition from the single collection — zero extra queries
        $pendingApproval = $activeOrders->where('status', 'pending_approval')->values();
        $receivedToday   = $activeOrders->where('status', 'received')
                               ->filter(fn ($o) => $o->created_at->gte($today))
                               ->values();
        $pendingOrders   = $activeOrders->whereIn('status', ['washing', 'drying', 'folding'])->values();
        $readyForPickup  = $activeOrders->where('status', 'ready_for_pickup')->values();

        // Stats: 2 targeted aggregate queries instead of 5
        $todayAggregates = Order::whereDate('created_at', $today)
            ->selectRaw("COUNT(*) as total_orders")
            ->selectRaw("SUM(CASE WHEN payment_status = 'paid' THEN total_price ELSE 0 END) as total_revenue")
            ->first();

        $todayStats = [
            'total_orders'    => $todayAggregates->total_orders,
            'total_revenue'   => $todayAggregates->total_revenue ?? 0,
            'pending_count'   => $pendingOrders->count(),
            'ready_count'     => $readyForPickup->count(),
            'approval_count'  => $pendingApproval->count(),
        ];

        return view('staff.dashboard', compact(
            'pendingApproval',
            'receivedToday',
            'pendingOrders',
            'readyForPickup',
            'todayStats',
        ));
    }
}
