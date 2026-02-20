@extends('layouts.app')

@section('title', 'Track Order – GeloWash')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="text-center">
        <h1 class="text-3xl font-bold text-gray-800">
            <svg class="inline-block w-8 h-8 text-sky-500 mr-1 -mt-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            Track Your Order
        </h1>
        <p class="text-gray-400 mt-1 text-sm">Enter your ticket number (e.g., GW-2026-0001)</p>
    </div>

    {{-- Search Form --}}
    <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl p-6">
        <form method="GET" action="{{ route('track.order') }}" class="flex gap-3">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                </div>
                <input type="text" name="ticket" value="{{ $searchTicket ?? '' }}" placeholder="GW-2026-XXXX"
                       class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-sky-300 focus:border-sky-400 outline-none transition bg-white/50 text-sm font-mono"
                       autofocus>
            </div>
            <button type="submit" class="bg-gradient-to-r from-sky-500 to-sky-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-sky-600 hover:to-sky-700 transition shadow-lg shadow-sky-200 min-h-[44px] whitespace-nowrap">
                Track
            </button>
        </form>
    </div>

    {{-- Single Order Result --}}
    @if(isset($order) && $order)
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl p-6 space-y-5">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-bold text-sky-600 font-mono">{{ $order->ticket_number }}</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Placed {{ $order->created_at->format('M d, Y – g:i A') }}</p>
                </div>
                @include('components.status-badge', ['status' => $order->status])
            </div>

            {{-- Timeline --}}
            <div class="pt-4 pb-2">
                @include('components.status-timeline', ['currentStatus' => $order->status])
            </div>

            {{-- Details Grid --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm pt-4 border-t border-gray-100">
                <div>
                    <span class="text-gray-400 text-xs block">Customer</span>
                    <p class="font-semibold text-gray-700">{{ $order->customer->name ?? 'Walk-in' }}</p>
                </div>
                <div>
                    <span class="text-gray-400 text-xs block">Weight</span>
                    <p class="font-semibold text-gray-700">{{ number_format($order->total_weight, 2) }} kg</p>
                </div>
                <div>
                    <span class="text-gray-400 text-xs block">Total</span>
                    <p class="font-semibold text-gray-700">₱{{ number_format($order->total_price, 2) }}</p>
                </div>
                <div>
                    <span class="text-gray-400 text-xs block">Payment</span>
                    <p class="font-semibold {{ $order->payment_status === 'paid' ? 'text-green-600' : 'text-amber-600' }}">
                        {{ ucfirst($order->payment_status) }}
                        @if($order->payment_method && $order->payment_method !== 'unpaid')
                            ({{ ucfirst($order->payment_method) }})
                        @endif
                    </p>
                </div>
            </div>

            @if($order->notes)
                <div class="bg-sky-50 rounded-xl px-4 py-3 text-sm text-gray-600">
                    <span class="font-semibold text-sky-600 text-xs block mb-1">Notes</span>
                    {{ $order->notes }}
                </div>
            @endif
        </div>
    @endif

    {{-- No result message --}}
    @if(isset($searchTicket) && $searchTicket && (!isset($order) || !$order) && (!isset($orders) || $orders->isEmpty()))
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl p-8 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                <path d="M10 10l4 4m0-4l-4 4" stroke-width="2"/>
            </svg>
            <p class="text-gray-500 font-medium">No order found for "{{ $searchTicket }}"</p>
            <p class="text-gray-400 text-sm mt-1">Please check the ticket number and try again</p>
        </div>
    @endif

    {{-- Multiple results (partial match) --}}
    @if(isset($orders) && $orders->count() > 0)
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-700">Matching Orders ({{ $orders->count() }})</h3>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($orders as $o)
                    <a href="{{ route('track.order', $o->ticket_number) }}" class="flex items-center justify-between px-6 py-4 hover:bg-sky-50/50 transition">
                        <div>
                            <span class="font-mono font-bold text-sky-600 text-sm">{{ $o->ticket_number }}</span>
                            <span class="text-xs text-gray-400 ml-2">{{ $o->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            @include('components.status-badge', ['status' => $o->status])
                            <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
