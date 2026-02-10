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

        $paidToday = Order::whereDate('created_at', $today)->where('payment_status', 'paid');
        $paidAll   = Order::where('payment_status', 'paid');

        $stats = [
            'total_orders_today'  => Order::whereDate('created_at', $today)->count(),
            'total_revenue_today' => (clone $paidToday)->sum('total_price'),
            'total_customers'     => User::where('role', 'customer')->count(),
            'pending_orders'      => Order::whereNotIn('status', ['collected'])->count(),
            'cash_today'          => (clone $paidToday)->where('payment_method', 'cash')->sum('total_price'),
            'gcash_today'         => (clone $paidToday)->where('payment_method', 'gcash')->sum('total_price'),
            'maya_today'          => (clone $paidToday)->where('payment_method', 'maya')->sum('total_price'),
            // Overall
            'total_orders'        => Order::count(),
            'total_revenue'       => (clone $paidAll)->sum('total_price'),
            'cash_all'            => (clone $paidAll)->where('payment_method', 'cash')->sum('total_price'),
            'gcash_all'           => (clone $paidAll)->where('payment_method', 'gcash')->sum('total_price'),
            'maya_all'            => (clone $paidAll)->where('payment_method', 'maya')->sum('total_price'),
        ];

        $recentOrders = Order::with('customer')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }
}
