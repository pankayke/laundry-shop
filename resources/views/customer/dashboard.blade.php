@extends('layouts.app')
@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">My Dashboard</h1>
    <p class="text-sm text-gray-500">Welcome back, {{ auth()->user()->name }}!</p>
</div>

{{-- Current Orders --}}
<section class="mb-10">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Current Orders</h2>

    @forelse ($currentOrders as $order)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                <div>
                    <span class="text-lg font-bold text-indigo-600">{{ $order->ticket_number }}</span>
                    @include('components.status-badge', ['status' => $order->status])
                </div>
                <div class="text-sm text-gray-500">
                    {{ $order->created_at->format('M d, Y h:i A') }}
                </div>
            </div>

            {{-- Timeline --}}
            <div class="mb-4">
                @include('components.status-timeline', ['currentStep' => $order->status_step])
            </div>

            <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                <span>Weight: <strong>{{ $order->total_weight }} kg</strong></span>
                <span>Total: <strong class="text-indigo-600">₱{{ number_format($order->total_price, 2) }}</strong></span>
                <span>Payment:
                    <strong class="{{ $order->isPaid() ? 'text-green-600' : 'text-red-500' }}">
                        {{ $order->isPaid() ? 'Paid (' . $order->payment_method_label . ')' : 'Unpaid' }}
                    </strong>
                </span>
            </div>

            {{-- Items --}}
            @if ($order->items->count())
                <div class="mt-3 border-t border-gray-100 pt-3">
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-xs text-gray-500">
                        @foreach ($order->items as $item)
                            <div class="bg-gray-50 rounded-lg px-3 py-2">
                                {{ $item->cloth_type }} · {{ $item->weight }}kg · {{ $item->service_type_label }}
                                <span class="block font-medium text-gray-700">₱{{ number_format($item->subtotal, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @empty
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center text-gray-400">
            <p class="text-lg mb-2">No active orders</p>
            <p class="text-sm">Visit our shop to drop off your laundry!</p>
        </div>
    @endforelse
</section>

{{-- Order History --}}
<section>
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Order History (Last 10)</h2>

    @forelse ($orderHistory as $order)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-3">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div class="flex items-center gap-3">
                    <span class="font-semibold text-gray-900">{{ $order->ticket_number }}</span>
                    @include('components.status-badge', ['status' => $order->status])
                </div>
                <div class="flex items-center gap-4 text-sm">
                    <span class="text-gray-500">{{ $order->created_at->format('M d, Y') }}</span>
                    <span class="font-semibold text-gray-900">₱{{ number_format($order->total_price, 2) }}</span>
                    @if (auth()->user()->isCustomer())
                        {{-- Repeat order shown for customer info, but action done by staff --}}
                    @endif
                </div>
            </div>
        </div>
    @empty
        <p class="text-sm text-gray-400">No completed orders yet.</p>
    @endforelse
</section>
@endsection
