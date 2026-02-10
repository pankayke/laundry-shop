@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Order {{ $order->ticket_number }}</h1>
        <div class="flex items-center gap-2">
            <a href="{{ route('receipt.download', $order) }}"
                class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 transition">
                📄 Download Receipt
            </a>
            <a href="{{ route('staff.orders.repeat', $order) }}"
                class="rounded-lg bg-indigo-50 px-4 py-2 text-sm font-medium text-indigo-700 hover:bg-indigo-100 transition">
                🔁 Repeat Order
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Order Details --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Status & Timeline --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Status</h2>
                <div class="mb-4">
                    @include('components.status-timeline', ['currentStep' => $order->status_step])
                </div>
                <form method="POST" action="{{ route('staff.orders.updateStatus', $order) }}" class="flex items-center gap-3">
                    @csrf @method('PATCH')
                    <select name="status"
                        class="flex-1 rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach (\App\Models\Order::STATUSES as $key => $label)
                            <option value="{{ $key }}" {{ $order->status === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button type="submit"
                        class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 transition">
                        Update Status
                    </button>
                </form>
            </div>

            {{-- Items --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Items</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-100 text-left text-xs font-medium text-gray-500 uppercase">
                                <th class="pb-3">Cloth Type</th>
                                <th class="pb-3">Service</th>
                                <th class="pb-3 text-right">Weight</th>
                                <th class="pb-3 text-right">Price/kg</th>
                                <th class="pb-3 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                <tr class="border-b border-gray-50">
                                    <td class="py-3 font-medium text-gray-900">{{ $item->cloth_type }}</td>
                                    <td class="py-3">{{ $item->service_type_label }}</td>
                                    <td class="py-3 text-right">{{ $item->weight }} kg</td>
                                    <td class="py-3 text-right">₱{{ number_format($item->price_per_kg, 2) }}</td>
                                    <td class="py-3 text-right font-medium">₱{{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="font-semibold">
                                <td colspan="2" class="pt-3">Total</td>
                                <td class="pt-3 text-right">{{ $order->total_weight }} kg</td>
                                <td></td>
                                <td class="pt-3 text-right text-indigo-600">₱{{ number_format($order->total_price, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Customer Info --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-sm font-semibold text-gray-800 mb-3">Customer</h2>
                <p class="font-medium text-gray-900">{{ $order->customer->name }}</p>
                <p class="text-sm text-gray-500">{{ $order->customer->phone }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $order->created_at->format('M d, Y h:i A') }}</p>
            </div>

            {{-- Payment --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-sm font-semibold text-gray-800 mb-3">Payment</h2>

                @if ($order->isPaid())
                    <div class="bg-green-50 rounded-lg p-4 text-center">
                        <p class="text-green-700 font-semibold">✅ Paid</p>
                        <p class="text-sm text-gray-600 mt-1">{{ $order->payment_method_label }}</p>
                        <p class="text-sm text-gray-600">Amount: ₱{{ number_format($order->amount_paid, 2) }}</p>
                        @if ($order->change_amount > 0)
                            <p class="text-sm text-gray-600">Change: ₱{{ number_format($order->change_amount, 2) }}</p>
                        @endif
                    </div>
                @else
                    <form method="POST" action="{{ route('staff.orders.updatePayment', $order) }}" class="space-y-3">
                        @csrf @method('PATCH')
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Payment Method</label>
                            <select name="payment_method" required
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="cash">Cash</option>
                                <option value="gcash">GCash</option>
                                <option value="maya">Maya</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Amount Received (₱)</label>
                            <input type="number" name="amount_paid" required step="0.01"
                                min="{{ $order->total_price }}" value="{{ $order->total_price }}"
                                id="amount_paid_input"
                                oninput="document.getElementById('change_display').textContent = '₱' + Math.max(0, parseFloat(this.value || 0) - {{ $order->total_price }}).toFixed(2)"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <p class="text-sm text-gray-600">
                            Due: <strong>₱{{ number_format($order->total_price, 2) }}</strong>
                            · Change: <strong id="change_display">₱0.00</strong>
                        </p>
                        <button type="submit"
                            class="w-full rounded-lg bg-green-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-green-700 transition">
                            Record Payment
                        </button>
                    </form>
                @endif
            </div>

            {{-- Notes --}}
            @if ($order->notes)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-sm font-semibold text-gray-800 mb-2">Notes</h2>
                    <p class="text-sm text-gray-600">{{ $order->notes }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
