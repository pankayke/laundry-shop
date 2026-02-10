@extends('layouts.app')
@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
    <p class="text-sm text-gray-500">{{ now()->format('l, F d, Y') }}</p>
</div>

{{-- Overall Stats --}}
<h2 class="text-lg font-semibold text-gray-700 mb-3">Overall</h2>
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Orders</p>
        <p class="mt-1 text-2xl font-bold text-gray-900">{{ $stats['total_orders'] }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Revenue</p>
        <p class="mt-1 text-2xl font-bold text-green-600">₱{{ number_format($stats['total_revenue'], 2) }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Customers</p>
        <p class="mt-1 text-2xl font-bold text-indigo-600">{{ $stats['total_customers'] }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Pending Orders</p>
        <p class="mt-1 text-2xl font-bold text-yellow-600">{{ $stats['pending_orders'] }}</p>
    </div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-xs font-medium text-gray-500 uppercase">Cash (All Time)</p>
        <p class="mt-1 text-xl font-bold text-gray-900">₱{{ number_format($stats['cash_all'], 2) }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-xs font-medium text-gray-500 uppercase">GCash (All Time)</p>
        <p class="mt-1 text-xl font-bold text-blue-600">₱{{ number_format($stats['gcash_all'], 2) }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-xs font-medium text-gray-500 uppercase">Maya (All Time)</p>
        <p class="mt-1 text-xl font-bold text-green-600">₱{{ number_format($stats['maya_all'], 2) }}</p>
    </div>
</div>

{{-- Today Stats --}}
<h2 class="text-lg font-semibold text-gray-700 mb-3">Today</h2>
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Orders Today</p>
        <p class="mt-1 text-2xl font-bold text-gray-900">{{ $stats['total_orders_today'] }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Revenue Today</p>
        <p class="mt-1 text-2xl font-bold text-green-600">₱{{ number_format($stats['total_revenue_today'], 2) }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-xs font-medium text-gray-500 uppercase">Cash Today</p>
        <p class="mt-1 text-xl font-bold text-gray-900">₱{{ number_format($stats['cash_today'], 2) }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-xs font-medium text-gray-500 uppercase">GCash / Maya Today</p>
        <p class="mt-1 text-xl font-bold text-blue-600">₱{{ number_format($stats['gcash_today'] + $stats['maya_today'], 2) }}</p>
    </div>
</div>

{{-- Recent Orders --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="text-lg font-semibold text-gray-800">Recent Orders</h2>
        <a href="{{ route('admin.sales') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">View All Sales →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">
                    <th class="px-4 py-3">Ticket</th>
                    <th class="px-4 py-3">Customer</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Total</th>
                    <th class="px-4 py-3">Payment</th>
                    <th class="px-4 py-3">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach ($recentOrders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-indigo-600">{{ $order->ticket_number }}</td>
                        <td class="px-4 py-3">{{ $order->customer->name }}</td>
                        <td class="px-4 py-3">@include('components.status-badge', ['status' => $order->status])</td>
                        <td class="px-4 py-3 text-right font-medium">₱{{ number_format($order->total_price, 2) }}</td>
                        <td class="px-4 py-3">
                            <span class="{{ $order->isPaid() ? 'text-green-600' : 'text-red-500' }} text-xs font-medium">
                                {{ $order->isPaid() ? $order->payment_method_label : 'Unpaid' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-500">{{ $order->created_at->format('M d, h:i A') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
