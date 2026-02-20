@extends('layouts.admin')

@section('title', 'Edit Order – GeloWash')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                Order <span class="text-sky-500 font-mono">{{ $order->ticket_number }}</span>
            </h1>
            <p class="text-gray-400 text-sm mt-1">{{ $order->created_at->format('M d, Y – g:i A') }} · {{ $order->customer->name ?? 'Walk-in' }}</p>
        </div>
        <a href="{{ route('staff.dashboard') }}" class="text-sm text-gray-500 hover:text-sky-600 transition flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back
        </a>
    </div>

    {{-- Status Timeline --}}
    <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl p-6">
        <h2 class="text-lg font-bold text-gray-700 mb-4">Order Progress</h2>
        @include('components.status-timeline', ['currentStatus' => $order->status])
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        {{-- Update Status --}}
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl p-6">
            <h2 class="text-lg font-bold text-gray-700 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-sky-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Update Status
            </h2>
            <form method="POST" action="{{ route('staff.orders.updateStatus', $order) }}">
                @csrf
                @method('PATCH')
                <div class="space-y-3">
                    @php $statuses = \App\Models\Order::STATUSES; @endphp
                    @foreach($statuses as $key => $label)
                        <label class="flex items-center gap-3 p-3 rounded-xl border transition cursor-pointer
                            {{ $order->status === $key ? 'border-sky-400 bg-sky-50 ring-2 ring-sky-200' : 'border-gray-200 hover:bg-sky-50/50' }}">
                            <input type="radio" name="status" value="{{ $key }}"
                                   {{ $order->status === $key ? 'checked' : '' }}
                                   class="w-4 h-4 text-sky-500 focus:ring-sky-300">
                            <span class="text-sm font-medium {{ $order->status === $key ? 'text-sky-700' : 'text-gray-600' }}">
                                {{ $label }}
                            </span>
                        </label>
                    @endforeach
                </div>
                <button type="submit"
                        class="mt-4 w-full bg-gradient-to-r from-sky-500 to-sky-600 text-white py-2.5 rounded-xl font-semibold hover:from-sky-600 hover:to-sky-700 transition shadow-lg shadow-sky-200 min-h-[44px]">
                    Update Status
                </button>
            </form>
        </div>

        {{-- Payment --}}
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl p-6">
            <h2 class="text-lg font-bold text-gray-700 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-sky-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Payment
            </h2>

            @if($order->payment_status === 'paid')
                <div class="bg-green-50 rounded-xl p-4 text-center">
                    <svg class="w-10 h-10 mx-auto text-green-500 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="font-semibold text-green-700">Payment Received</p>
                    <p class="text-sm text-green-600 mt-1">{{ ucfirst($order->payment_method) }} · ₱{{ number_format($order->amount_paid, 2) }}</p>
                    @if($order->change_amount > 0)
                        <p class="text-xs text-green-500 mt-0.5">Change: ₱{{ number_format($order->change_amount, 2) }}</p>
                    @endif
                </div>
            @else
                <div class="mb-4 bg-amber-50 rounded-xl p-4 text-center">
                    <p class="text-amber-700 font-semibold">Amount Due: ₱{{ number_format($order->total_price, 2) }}</p>
                </div>

                <form method="POST" action="{{ route('staff.orders.updatePayment', $order) }}" class="space-y-4" x-data="paymentForm()">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="text-sm font-medium text-gray-600 mb-2 block">Method</label>
                        <div class="grid grid-cols-3 gap-2">
                            @foreach(['cash' => 'Cash', 'gcash' => 'GCash', 'maya' => 'Maya'] as $key => $label)
                                <label class="flex items-center justify-center gap-1 p-2.5 rounded-xl border cursor-pointer transition text-sm font-medium"
                                       :class="method === '{{ $key }}' ? 'border-sky-400 bg-sky-50 text-sky-700 ring-2 ring-sky-200' : 'border-gray-200 text-gray-500 hover:bg-sky-50/50'">
                                    <input type="radio" name="payment_method" value="{{ $key }}" x-model="method" class="sr-only">
                                    {{ $label }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-600 mb-1 block">Amount Paid</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 font-semibold">₱</span>
                            <input type="number" name="amount_paid" x-model="amount" step="0.01" min="{{ $order->total_price }}" required
                                   class="w-full pl-8 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-sky-300 focus:border-sky-400 outline-none transition text-sm bg-white/50">
                        </div>
                    </div>

                    <div x-show="change > 0" class="bg-green-50 rounded-xl px-4 py-2 text-sm text-green-700 font-medium text-center">
                        Change: ₱<span x-text="change.toFixed(2)"></span>
                    </div>

                    <button type="submit"
                            class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white py-2.5 rounded-xl font-semibold hover:from-green-600 hover:to-green-700 transition shadow-lg shadow-green-200 min-h-[44px]">
                        Record Payment
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- Order Details --}}
    <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl p-6">
        <h2 class="text-lg font-bold text-gray-700 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-sky-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><circle cx="12" cy="13" r="4"/><rect x="8" y="5" width="8" height="4" rx="2"/></svg>
            Items
        </h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="px-4 py-2 text-left font-semibold text-gray-600">Cloth Type</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-600">Service</th>
                        <th class="px-4 py-2 text-right font-semibold text-gray-600">Weight</th>
                        <th class="px-4 py-2 text-right font-semibold text-gray-600">Rate</th>
                        <th class="px-4 py-2 text-right font-semibold text-gray-600">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($order->items as $item)
                        <tr class="hover:bg-sky-50/30 transition">
                            <td class="px-4 py-3 font-medium text-gray-700">{{ $item->cloth_type }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ ucfirst($item->service_type) }}</td>
                            <td class="px-4 py-3 text-right text-gray-600">{{ number_format($item->weight, 2) }} kg</td>
                            <td class="px-4 py-3 text-right text-gray-500">₱{{ number_format($item->price_per_kg, 2) }}/kg</td>
                            <td class="px-4 py-3 text-right font-semibold text-gray-700">₱{{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="border-t-2 border-gray-200">
                        <td colspan="2" class="px-4 py-3 text-right font-bold text-gray-700">Total</td>
                        <td class="px-4 py-3 text-right font-bold text-gray-700">{{ number_format($order->total_weight, 2) }} kg</td>
                        <td></td>
                        <td class="px-4 py-3 text-right font-bold text-sky-600 text-lg">₱{{ number_format($order->total_price, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        @if($order->notes)
            <div class="mt-4 bg-sky-50 rounded-xl p-4 text-sm text-gray-600">
                <span class="font-semibold text-sky-600 text-xs block mb-1">Notes</span>
                {{ $order->notes }}
            </div>
        @endif
    </div>

    {{-- Actions --}}
    <div class="flex gap-3">
        <a href="{{ route('receipt.download', $order) }}" class="flex items-center gap-2 bg-gray-100 text-gray-700 px-5 py-2.5 rounded-xl font-semibold hover:bg-gray-200 transition min-h-[44px]">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Print Receipt
        </a>
        <a href="{{ route('staff.orders.repeat', $order) }}" class="flex items-center gap-2 bg-sky-100 text-sky-700 px-5 py-2.5 rounded-xl font-semibold hover:bg-sky-200 transition min-h-[44px]">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            Repeat Order
        </a>
    </div>
</div>

@push('scripts')
<script>
function paymentForm() {
    return {
        method: 'cash',
        amount: {{ $order->total_price }},
        get change() {
            return Math.max(0, (parseFloat(this.amount) || 0) - {{ $order->total_price }});
        }
    };
}
</script>
@endpush
@endsection
