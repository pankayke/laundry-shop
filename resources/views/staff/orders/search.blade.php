@extends('layouts.admin')

@section('title', 'Search Orders – GeloWash')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Search Orders</h1>
            <p class="text-gray-400 text-sm mt-1">Find orders by ticket, customer name, or phone</p>
        </div>
        <a href="{{ route('staff.orders.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-sky-500 to-sky-600 text-white px-5 py-2.5 rounded-xl font-semibold hover:from-sky-600 hover:to-sky-700 transition shadow-lg shadow-sky-200 min-h-[44px]">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
            New Order
        </a>
    </div>

    {{-- Search Form --}}
    <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl p-6">
        <form method="GET" action="{{ route('staff.orders.search') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ticket number, customer name, or phone"
                       class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-sky-300 focus:border-sky-400 outline-none transition bg-white/50 text-sm">
            </div>
            <select name="status" class="px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-sky-300 focus:border-sky-400 outline-none transition bg-white/50 text-sm">
                <option value="">All Statuses</option>
                @foreach(\App\Models\Order::STATUSES as $key => $label)
                    <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-gradient-to-r from-sky-500 to-sky-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-sky-600 hover:to-sky-700 transition shadow-lg shadow-sky-200 min-h-[44px]">
                Search
            </button>
        </form>
    </div>

    {{-- Results --}}
    @if($orders->count())
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50/80 border-b border-gray-100">
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Ticket</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Customer</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Status</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-600">Weight</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-600">Total</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Payment</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Date</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($orders as $order)
                            <tr class="hover:bg-sky-50/50 transition">
                                <td class="px-4 py-3 font-mono font-bold text-sky-600">{{ $order->ticket_number }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $order->customer->name ?? 'Walk-in' }}</td>
                                <td class="px-4 py-3">@include('components.status-badge', ['status' => $order->status])</td>
                                <td class="px-4 py-3 text-right text-gray-600">{{ number_format($order->total_weight, 2) }} kg</td>
                                <td class="px-4 py-3 text-right font-semibold text-gray-700">₱{{ number_format($order->total_price, 2) }}</td>
                                <td class="px-4 py-3">
                                    <span class="{{ $order->payment_status === 'paid' ? 'text-green-600' : 'text-amber-600' }} font-medium">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-500">{{ $order->created_at->format('M d, g:ia') }}</td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('staff.orders.edit', $order) }}" class="p-2 rounded-lg text-sky-600 hover:bg-sky-50 transition" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <a href="{{ route('receipt.download', $order) }}" class="p-2 rounded-lg text-gray-500 hover:bg-gray-50 transition" title="Receipt">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($orders->hasPages())
                <div class="px-4 py-3 border-t border-gray-100">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    @else
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl p-8 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <p class="text-gray-500 font-medium">No orders found</p>
            <p class="text-gray-400 text-sm mt-1">Try a different search term or filter</p>
        </div>
    @endif
</div>
@endsection
