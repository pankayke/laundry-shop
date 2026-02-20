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

        $settings = Setting::instance();

        return view('customer.dashboard', compact('activeOrders', 'pastOrders', 'settings'));
    }
}
