{{-- Reusable order card for staff views --}}
<div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl hover:shadow-2xl transition-all p-5 space-y-3">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <span class="font-mono font-bold text-sky-600 text-sm">{{ $order->ticket_number }}</span>
        @include('components.status-badge', ['status' => $order->status])
    </div>

    {{-- Customer --}}
    <div class="flex items-center gap-2 text-sm text-gray-600">
        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0"/>
        </svg>
        <span class="truncate">{{ $order->customer->name ?? 'Walk-in' }}</span>
    </div>

    {{-- Details --}}
    <div class="grid grid-cols-2 gap-2 text-sm">
        <div class="bg-sky-50/60 rounded-xl px-3 py-2 border border-sky-100/40">
            <span class="text-gray-400 text-xs block">Weight</span>
            <span class="font-semibold text-gray-700">{{ number_format($order->total_weight, 2) }} kg</span>
        </div>
        <div class="bg-sky-50/60 rounded-xl px-3 py-2 border border-sky-100/40">
            <span class="text-gray-400 text-xs block">Total</span>
            <span class="font-semibold text-gray-700">₱{{ number_format($order->total_price, 2) }}</span>
        </div>
    </div>

    {{-- Payment --}}
    <div class="flex items-center justify-between text-sm">
        <span class="inline-flex items-center gap-1 {{ $order->payment_status === 'paid' ? 'text-green-600' : 'text-amber-600' }}">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                @if($order->payment_status === 'paid')
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                @else
                    <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                @endif
            </svg>
            {{ ucfirst($order->payment_status) }}
            @if($order->payment_method && $order->payment_method !== 'unpaid')
                · {{ ucfirst($order->payment_method) }}
            @endif
        </span>
        <span class="text-xs text-gray-400">{{ $order->created_at->format('g:i A') }}</span>
    </div>

    {{-- Actions --}}
    <div class="flex gap-2 pt-2 border-t border-white/40">
        <a href="{{ route('staff.orders.edit', $order) }}" class="flex-1 flex items-center justify-center gap-1 bg-sky-100 text-sky-700 px-3 py-2 rounded-xl text-sm font-semibold hover:bg-sky-200 transition min-h-[40px]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit
        </a>
        <a href="{{ route('receipt.download', $order) }}" class="flex items-center justify-center gap-1 bg-gray-100 text-gray-600 px-3 py-2 rounded-xl text-sm font-semibold hover:bg-gray-200 transition min-h-[40px]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Receipt
        </a>
    </div>
</div>
