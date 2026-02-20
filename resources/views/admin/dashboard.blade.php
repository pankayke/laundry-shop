@extends('layouts.admin')

@section('title', 'Admin Dashboard – GeloWash')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Admin Dashboard</h1>
            <p class="text-gray-400 text-sm mt-1">{{ now()->format('l, F d, Y') }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('staff.orders.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-[#87CEEB] to-sky-500 text-white px-5 py-2.5 rounded-xl font-semibold hover:shadow-lg transition shadow-md min-h-[44px]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-linecap="round"/></svg>
                New Order
            </a>
        </div>
    </div>

    {{-- Today's Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl p-5 hover:shadow-2xl transition-all">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-sky-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-sky-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><circle cx="12" cy="13" r="4"/><rect x="8" y="5" width="8" height="4" rx="2"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total_orders_today'] }}</p>
                    <p class="text-xs text-gray-400">Orders Today</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl p-5 hover:shadow-2xl transition-all">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-green-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">₱{{ number_format($stats['total_revenue_today'], 0) }}</p>
                    <p class="text-xs text-gray-400">Revenue Today</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl p-5 hover:shadow-2xl transition-all">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-purple-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total_customers'] }}</p>
                    <p class="text-xs text-gray-400">Customers</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl p-5 hover:shadow-2xl transition-all">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-amber-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['pending_orders'] }}</p>
                    <p class="text-xs text-gray-400">Pending Orders</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Revenue + Pie Chart Row --}}
    <div class="grid gap-6 lg:grid-cols-2">
        {{-- Overall Revenue --}}
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl p-6">
            <h2 class="text-lg font-bold text-gray-700 mb-4">Overall Revenue</h2>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Total Orders</span>
                    <span class="font-bold text-gray-800">{{ number_format($stats['total_orders']) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Total Revenue</span>
                    <span class="font-bold text-green-600 text-lg">₱{{ number_format($stats['total_revenue'], 2) }}</span>
                </div>
                <hr class="border-gray-100">
                <div class="flex justify-between items-center">
                    <span class="text-gray-500 flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-green-500"></span> Cash</span>
                    <span class="font-semibold text-gray-700">₱{{ number_format($stats['cash_all'], 2) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500 flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-blue-500"></span> GCash</span>
                    <span class="font-semibold text-gray-700">₱{{ number_format($stats['gcash_all'], 2) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500 flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-purple-500"></span> Maya</span>
                    <span class="font-semibold text-gray-700">₱{{ number_format($stats['maya_all'], 2) }}</span>
                </div>
            </div>
        </div>

        {{-- SVG Pie Chart - Today's Revenue Breakdown --}}
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl p-6">
            <h2 class="text-lg font-bold text-gray-700 mb-4">Today's Revenue Breakdown</h2>
            @php
                $todayTotal = max(1, $stats['cash_today'] + $stats['gcash_today'] + $stats['maya_today']);
                $cashPct   = $stats['cash_today'] / $todayTotal * 100;
                $gcashPct  = $stats['gcash_today'] / $todayTotal * 100;
                $mayaPct   = $stats['maya_today'] / $todayTotal * 100;

                // SVG donut chart math
                $radius = 80;
                $circumference = 2 * pi() * $radius;
                $cashLen  = $circumference * $cashPct / 100;
                $gcashLen = $circumference * $gcashPct / 100;
                $mayaLen  = $circumference * $mayaPct / 100;

                $cashOffset  = 0;
                $gcashOffset = -$cashLen;
                $mayaOffset  = -($cashLen + $gcashLen);
            @endphp

            <div class="flex items-center justify-center gap-8">
                <div class="relative">
                    <svg viewBox="0 0 200 200" class="w-40 h-40">
                        @if($todayTotal > 1)
                            {{-- Cash --}}
                            <circle cx="100" cy="100" r="{{ $radius }}" fill="none" stroke="#22C55E" stroke-width="24"
                                    stroke-dasharray="{{ $cashLen }} {{ $circumference - $cashLen }}"
                                    stroke-dashoffset="{{ $cashOffset }}" transform="rotate(-90 100 100)"/>
                            {{-- GCash --}}
                            <circle cx="100" cy="100" r="{{ $radius }}" fill="none" stroke="#3B82F6" stroke-width="24"
                                    stroke-dasharray="{{ $gcashLen }} {{ $circumference - $gcashLen }}"
                                    stroke-dashoffset="{{ $gcashOffset }}" transform="rotate(-90 100 100)"/>
                            {{-- Maya --}}
                            <circle cx="100" cy="100" r="{{ $radius }}" fill="none" stroke="#A855F7" stroke-width="24"
                                    stroke-dasharray="{{ $mayaLen }} {{ $circumference - $mayaLen }}"
                                    stroke-dashoffset="{{ $mayaOffset }}" transform="rotate(-90 100 100)"/>
                        @else
                            <circle cx="100" cy="100" r="{{ $radius }}" fill="none" stroke="#E5E7EB" stroke-width="24"/>
                        @endif
                        {{-- Center text --}}
                        <text x="100" y="95" text-anchor="middle" class="text-xs fill-gray-400" font-size="12">Today</text>
                        <text x="100" y="115" text-anchor="middle" class="text-sm fill-gray-800 font-bold" font-size="16">₱{{ number_format($stats['total_revenue_today'], 0) }}</text>
                    </svg>
                </div>

                <div class="space-y-2 text-sm">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-green-500 shrink-0"></span>
                        <span class="text-gray-500">Cash</span>
                        <span class="ml-auto font-bold text-gray-700">{{ number_format($cashPct, 0) }}%</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-blue-500 shrink-0"></span>
                        <span class="text-gray-500">GCash</span>
                        <span class="ml-auto font-bold text-gray-700">{{ number_format($gcashPct, 0) }}%</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-purple-500 shrink-0"></span>
                        <span class="text-gray-500">Maya</span>
                        <span class="ml-auto font-bold text-gray-700">{{ number_format($mayaPct, 0) }}%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Orders --}}
    <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-700">Recent Orders</h2>
            <a href="{{ route('admin.sales') }}" class="text-sm text-sky-600 hover:text-sky-700 font-semibold flex items-center gap-1">
                View All
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100">
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Ticket</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Customer</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Status</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">Total</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Payment</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentOrders as $order)
                        <tr class="hover:bg-sky-50/50 transition">
                            <td class="px-4 py-3 font-mono font-bold text-sky-600">{{ $order->ticket_number }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $order->customer->name ?? 'Walk-in' }}</td>
                            <td class="px-4 py-3">@include('components.status-badge', ['status' => $order->status])</td>
                            <td class="px-4 py-3 text-right font-semibold text-gray-700">₱{{ number_format($order->total_price, 2) }}</td>
                            <td class="px-4 py-3">
                                <span class="{{ $order->payment_status === 'paid' ? 'text-green-600' : 'text-amber-600' }} font-medium">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ $order->created_at->format('M d, g:ia') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-400">No orders yet</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
