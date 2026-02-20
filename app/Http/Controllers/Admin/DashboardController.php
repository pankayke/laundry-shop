<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->startOfDay();

        // Single aggregate query for today's stats
        $todayAgg = Order::whereDate('created_at', $today)
            ->selectRaw("COUNT(*) as total_orders")
            ->selectRaw("SUM(CASE WHEN payment_status = 'paid' THEN total_price ELSE 0 END) as revenue")
            ->selectRaw("SUM(CASE WHEN payment_status = 'paid' AND payment_method = 'cash'  THEN total_price ELSE 0 END) as cash")
            ->selectRaw("SUM(CASE WHEN payment_status = 'paid' AND payment_method = 'gcash' THEN total_price ELSE 0 END) as gcash")
            ->selectRaw("SUM(CASE WHEN payment_status = 'paid' AND payment_method = 'maya'  THEN total_price ELSE 0 END) as maya")
            ->first();

        // Single aggregate query for all-time stats
        $allAgg = Order::query()
            ->selectRaw("COUNT(*) as total_orders")
            ->selectRaw("SUM(CASE WHEN payment_status = 'paid' THEN total_price ELSE 0 END) as revenue")
            ->selectRaw("SUM(CASE WHEN payment_status = 'paid' AND payment_method = 'cash'  THEN total_price ELSE 0 END) as cash")
            ->selectRaw("SUM(CASE WHEN payment_status = 'paid' AND payment_method = 'gcash' THEN total_price ELSE 0 END) as gcash")
            ->selectRaw("SUM(CASE WHEN payment_status = 'paid' AND payment_method = 'maya'  THEN total_price ELSE 0 END) as maya")
            ->first();

        $stats = [
            'total_orders_today'  => $todayAgg->total_orders,
            'total_revenue_today' => $todayAgg->revenue ?? 0,
            'total_customers'     => User::where('role', 'customer')->count(),
            'pending_orders'      => Order::whereNotIn('status', ['collected'])->count(),
            'cash_today'          => $todayAgg->cash ?? 0,
            'gcash_today'         => $todayAgg->gcash ?? 0,
            'maya_today'          => $todayAgg->maya ?? 0,
            'total_orders'        => $allAgg->total_orders,
            'total_revenue'       => $allAgg->revenue ?? 0,
            'cash_all'            => $allAgg->cash ?? 0,
            'gcash_all'           => $allAgg->gcash ?? 0,
            'maya_all'            => $allAgg->maya ?? 0,
        ];

        $recentOrders = Order::with('customer')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }
}
