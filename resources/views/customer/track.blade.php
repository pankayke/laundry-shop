@extends('layouts.app')
@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-900 mb-6 text-center">Track Your Order</h1>

    {{-- Search form --}}
    <form method="GET" action="{{ route('track.order') }}" class="flex gap-3 mb-8">
        <input type="text" name="ticket" value="{{ $searchTicket ?? '' }}" required
            class="flex-1 rounded-lg border border-gray-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            placeholder="Ticket number, phone number, or name">
        <button type="submit"
            class="rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 transition">
            Track
        </button>
    </form>

    @if ($searchTicket && !$order && $orders->isEmpty())
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center text-sm text-yellow-800">
            No order found for <strong>{{ $searchTicket }}</strong>. Please check and try again.
        </div>
    @endif

    {{-- Multiple results (phone/name search) --}}
    @if ($orders->isNotEmpty())
        <div class="mb-6">
            <p class="text-sm text-gray-600 mb-3">Found <strong>{{ $orders->count() }}</strong> orders matching <strong>{{ $searchTicket }}</strong>:</p>
            <div class="space-y-3">
                @foreach ($orders as $o)
                    <a href="{{ route('track.order', ['ticket' => $o->ticket_number]) }}"
                       class="block bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:border-indigo-300 transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="font-bold text-indigo-600">{{ $o->ticket_number }}</span>
                                @include('components.status-badge', ['status' => $o->status])
                            </div>
                            <span class="text-sm text-gray-500">{{ $o->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="mt-2 text-sm text-gray-600">
                            {{ $o->customer->name }} &middot; {{ $o->total_weight }}kg &middot;
                            <span class="font-medium">₱{{ number_format($o->total_price, 2) }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    @if ($order)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
                <div>
                    <span class="text-xl font-bold text-indigo-600">{{ $order->ticket_number }}</span>
                    @include('components.status-badge', ['status' => $order->status])
                </div>
                <span class="text-sm text-gray-500">{{ $order->created_at->format('M d, Y h:i A') }}</span>
            </div>

            {{-- Timeline --}}
            <div class="mb-6">
                @include('components.status-timeline', ['currentStep' => $order->status_step])
            </div>

            {{-- Details --}}
            <div class="grid grid-cols-2 gap-4 text-sm mb-6">
                <div>
                    <span class="text-gray-500">Customer</span>
                    <p class="font-medium text-gray-900">{{ $order->customer->name }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Total Weight</span>
                    <p class="font-medium text-gray-900">{{ $order->total_weight }} kg</p>
                </div>
                <div>
                    <span class="text-gray-500">Total Price</span>
                    <p class="font-bold text-indigo-600">₱{{ number_format($order->total_price, 2) }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Payment</span>
                    <p class="font-medium {{ $order->isPaid() ? 'text-green-600' : 'text-red-500' }}">
                        {{ $order->isPaid() ? 'Paid (' . $order->payment_method_label . ')' : 'Unpaid' }}
                    </p>
                </div>
            </div>

            {{-- Items --}}
            @if ($order->items->count())
                <div class="border-t border-gray-100 pt-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Items</h3>
                    <div class="space-y-2">
                        @foreach ($order->items as $item)
                            <div class="flex items-center justify-between bg-gray-50 rounded-lg px-4 py-2 text-sm">
                                <div>
                                    <span class="font-medium text-gray-900">{{ $item->cloth_type }}</span>
                                    <span class="text-gray-500">· {{ $item->weight }}kg · {{ $item->service_type_label }}</span>
                                </div>
                                <span class="font-medium text-gray-900">₱{{ number_format($item->subtotal, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif
</div>
@endsection
