<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $currentOrders = $user->orders()
            ->whereNotIn('status', ['collected'])
            ->with('items')
            ->orderByDesc('created_at')
            ->get();

        $orderHistory = $user->orders()
            ->where('status', 'collected')
            ->with('items')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('customer.dashboard', compact('currentOrders', 'orderHistory'));
    }
}
