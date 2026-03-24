<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $activeOrders = $user->orders()
            ->whereNotIn('status', ['collected', 'cancelled'])
            ->with('items')
            ->orderByDesc('created_at')
            ->get();

        $pastOrders = $user->orders()
            ->whereIn('status', ['collected', 'cancelled'])
            ->with('items')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $orderCounts = [
            'active' => $activeOrders->count(),
            'pending' => $activeOrders->where('status', 'pending_approval')->count(),
            'ready' => $activeOrders->where('status', 'ready_for_pickup')->count(),
            'completed' => $pastOrders->where('status', 'collected')->count(),
        ];

        $settings = Setting::instance();

        return view('customer.dashboard', compact('activeOrders', 'pastOrders', 'orderCounts', 'settings'));
    }
}
