{{-- Reusable order card for staff dashboard --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-3">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        {{-- Left: ticket info --}}
        <div class="flex-1">
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('staff.orders.edit', $order) }}" class="font-bold text-indigo-600 hover:underline">{{ $order->ticket_number }}</a>
                @include('components.status-badge', ['status' => $order->status])
                @if ($order->isPaid())
                    <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">Paid</span>
                @else
                    <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700">Unpaid</span>
                @endif
            </div>
            <p class="text-sm text-gray-600 mt-1">
                {{ $order->customer->name }} · {{ $order->customer->phone }}
                <span class="text-gray-400 ml-2">{{ $order->total_weight }}kg · ₱{{ number_format($order->total_price, 2) }}</span>
            </p>
        </div>

        {{-- Right: quick actions --}}
        <div class="flex items-center gap-2 flex-wrap">
            {{-- Status dropdown --}}
            <form method="POST" action="{{ route('staff.orders.updateStatus', $order) }}" class="flex items-center gap-1">
                @csrf @method('PATCH')
                <select name="status" onchange="this.form.submit()"
                    class="rounded-lg border border-gray-300 text-xs py-1.5 px-2 focus:border-indigo-500 focus:ring-indigo-500">
                    @foreach (\App\Models\Order::STATUSES as $key => $label)
                        <option value="{{ $key }}" {{ $order->status === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </form>

            {{-- Mark paid (if unpaid) --}}
            @if (! $order->isPaid())
                <a href="{{ route('staff.orders.edit', $order) }}"
                    class="rounded-lg bg-green-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-green-700 transition">
                    Mark Paid
                </a>
            @endif

            <a href="{{ route('receipt.download', $order) }}"
                class="rounded-lg bg-gray-100 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-200 transition">
                Receipt
            </a>
        </div>
    </div>
</div>
