@extends('layouts.admin')

@section('title', 'Staff Dashboard – GeloWash')

@section('content')
<div class="max-w-7xl mx-auto space-y-6" x-data="{ tab: '{{ $pendingApproval->count() > 0 ? 'approval' : 'received' }}' }">
    {{-- Toast Notifications --}}
    @if(session('success'))
        @include('components.glass-toast', ['message' => session('success'), 'type' => 'success'])
    @endif
    @if(session('error'))
        @include('components.glass-toast', ['message' => session('error'), 'type' => 'error'])
    @endif
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Staff Dashboard</h1>
            <p class="text-gray-400 text-sm mt-1">{{ now()->format('l, F d, Y') }}</p>
        </div>
        <a href="{{ route('staff.orders.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-sky-500 to-sky-600 text-white px-5 py-2.5 rounded-xl font-semibold hover:from-sky-600 hover:to-sky-700 transition shadow-lg shadow-sky-200 min-h-[44px]">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
            New Order
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        {{-- Pending Approval --}}
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl p-5 hover:shadow-2xl transition-all {{ $todayStats['approval_count'] > 0 ? 'ring-2 ring-red-200' : '' }}">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl {{ $todayStats['approval_count'] > 0 ? 'bg-red-100' : 'bg-gray-100' }} flex items-center justify-center">
                    <svg class="w-6 h-6 {{ $todayStats['approval_count'] > 0 ? 'text-red-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold {{ $todayStats['approval_count'] > 0 ? 'text-red-600' : 'text-gray-800' }}">{{ $todayStats['approval_count'] }}</p>
                    <p class="text-xs text-gray-400">Pending Approval</p>
                </div>
            </div>
        </div>

        {{-- Today's Orders --}}
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl p-5 hover:shadow-2xl transition-all">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-sky-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-sky-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><circle cx="12" cy="13" r="4"/><rect x="8" y="5" width="8" height="4" rx="2"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $todayStats['total_orders'] }}</p>
                    <p class="text-xs text-gray-400">Orders Today</p>
                </div>
            </div>
        </div>

        {{-- Revenue --}}
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl p-5 hover:shadow-2xl transition-all">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-green-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">₱{{ number_format($todayStats['total_revenue'], 0) }}</p>
                    <p class="text-xs text-gray-400">Revenue Today</p>
                </div>
            </div>
        </div>

        {{-- Pending --}}
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl p-5 hover:shadow-2xl transition-all">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-amber-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $todayStats['pending_count'] }}</p>
                    <p class="text-xs text-gray-400">In Progress</p>
                </div>
            </div>
        </div>

        {{-- Ready --}}
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl p-5 hover:shadow-2xl transition-all">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-green-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $todayStats['ready_count'] }}</p>
                    <p class="text-xs text-gray-400">Ready for Pickup</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl overflow-hidden">
        <div class="flex border-b border-gray-100 overflow-x-auto">
            <button @click="tab = 'approval'" :class="tab === 'approval' ? 'border-red-500 text-red-600 bg-red-50/50' : 'border-transparent text-gray-500 hover:text-red-500'"
                    class="flex items-center gap-2 px-5 py-3.5 text-sm font-semibold border-b-2 transition whitespace-nowrap min-h-[44px]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Pending Requests
                @if($pendingApproval->count() > 0)
                    <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full animate-pulse">{{ $pendingApproval->count() }}</span>
                @else
                    <span class="bg-gray-200 text-gray-500 text-xs font-bold px-2 py-0.5 rounded-full">0</span>
                @endif
            </button>
            <button @click="tab = 'received'" :class="tab === 'received' ? 'border-sky-500 text-sky-600 bg-sky-50/50' : 'border-transparent text-gray-500 hover:text-sky-500'"
                    class="flex items-center gap-2 px-5 py-3.5 text-sm font-semibold border-b-2 transition whitespace-nowrap min-h-[44px]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Received Today
                <span class="bg-blue-100 text-blue-600 text-xs font-bold px-2 py-0.5 rounded-full">{{ $receivedToday->count() }}</span>
            </button>
            <button @click="tab = 'pending'" :class="tab === 'pending' ? 'border-sky-500 text-sky-600 bg-sky-50/50' : 'border-transparent text-gray-500 hover:text-sky-500'"
                    class="flex items-center gap-2 px-5 py-3.5 text-sm font-semibold border-b-2 transition whitespace-nowrap min-h-[44px]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                In Progress
                <span class="bg-amber-100 text-amber-600 text-xs font-bold px-2 py-0.5 rounded-full">{{ $pendingOrders->count() }}</span>
            </button>
            <button @click="tab = 'ready'" :class="tab === 'ready' ? 'border-sky-500 text-sky-600 bg-sky-50/50' : 'border-transparent text-gray-500 hover:text-sky-500'"
                    class="flex items-center gap-2 px-5 py-3.5 text-sm font-semibold border-b-2 transition whitespace-nowrap min-h-[44px]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Ready for Pickup
                <span class="bg-green-100 text-green-600 text-xs font-bold px-2 py-0.5 rounded-full">{{ $readyForPickup->count() }}</span>
            </button>
        </div>

        {{-- Tab Content --}}
        <div class="p-4 sm:p-6">
            {{-- Pending Approval --}}
            <div x-show="tab === 'approval'" x-transition>
                @if($pendingApproval->isEmpty())
                    <p class="text-center text-gray-400 py-8">No pending requests</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gradient-to-r from-red-50 to-amber-50 border-b border-red-100">
                                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Ticket</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Customer</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Weight</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Services</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Est. Total</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Payment</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Date</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($pendingApproval as $order)
                                    <tr class="hover:bg-red-50/50 transition">
                                        <td class="px-4 py-3 font-mono text-sky-600 font-semibold text-xs">{{ $order->ticket_number }}</td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 rounded-full bg-sky-100 flex items-center justify-center text-sky-700 text-xs font-bold">
                                                    {{ strtoupper(substr($order->customer->name ?? '?', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="font-medium text-gray-700">{{ $order->customer->name ?? 'N/A' }}</p>
                                                    <p class="text-xs text-gray-400">{{ $order->customer->phone ?? '' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 font-semibold text-gray-700">{{ number_format($order->estimated_weight ?? $order->total_weight, 2) }} kg</td>
                                        <td class="px-4 py-3">
                                            @if($order->requested_services)
                                                @foreach($order->requested_services as $service)
                                                    @php
                                                        $svcColors = ['wash' => 'bg-sky-100 text-sky-700', 'dry' => 'bg-amber-100 text-amber-700', 'fold' => 'bg-purple-100 text-purple-700'];
                                                    @endphp
                                                    <span class="inline-block px-2 py-0.5 {{ $svcColors[$service] ?? 'bg-gray-100 text-gray-600' }} rounded-full text-xs font-semibold mr-1">{{ ucfirst($service) }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-gray-400 text-xs">–</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 font-semibold text-gray-700">₱{{ number_format($order->total_price, 2) }}</td>
                                        <td class="px-4 py-3">
                                            @include('components.payment-badge', ['order' => $order])
                                            @if($order->payment_reference)
                                                <div class="mt-1">
                                                    <span class="font-mono text-[10px] bg-gray-50 border border-gray-200 px-1.5 py-0.5 rounded text-gray-500">{{ $order->payment_reference }}</span>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-gray-500 text-xs">{{ $order->created_at->format('M d, g:ia') }}</td>
                                        <td class="px-4 py-3">
                                            <form action="{{ route('staff.orders.approve', $order) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="inline-flex items-center gap-1.5 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-xl text-xs font-semibold transition shadow-sm hover:shadow-md min-h-[36px]">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    Approve
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- Received --}}
            <div x-show="tab === 'received'" x-transition>
                @if($receivedToday->isEmpty())
                    <p class="text-center text-gray-400 py-8">No new orders received today</p>
                @else
                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($receivedToday as $order)
                            @include('staff._order-card', ['order' => $order])
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Pending --}}
            <div x-show="tab === 'pending'" x-transition>
                @if($pendingOrders->isEmpty())
                    <p class="text-center text-gray-400 py-8">No orders in progress</p>
                @else
                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($pendingOrders as $order)
                            @include('staff._order-card', ['order' => $order])
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Ready --}}
            <div x-show="tab === 'ready'" x-transition>
                @if($readyForPickup->isEmpty())
                    <p class="text-center text-gray-400 py-8">No orders ready for pickup</p>
                @else
                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($readyForPickup as $order)
                            @include('staff._order-card', ['order' => $order])
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
