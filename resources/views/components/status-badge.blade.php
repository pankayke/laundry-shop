{{--
    Status badge component.
    Usage: @include('components.status-badge', ['status' => $order->status])
--}}
@php
$colors = [
    'received'         => 'bg-yellow-100 text-yellow-800',
    'washing'          => 'bg-blue-100 text-blue-800',
    'drying'           => 'bg-cyan-100 text-cyan-800',
    'folding'          => 'bg-purple-100 text-purple-800',
    'ready_for_pickup' => 'bg-green-100 text-green-800',
    'collected'        => 'bg-gray-100 text-gray-800',
];
$labels = \App\Models\Order::STATUSES;
@endphp

<span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $colors[$status] ?? 'bg-gray-100 text-gray-800' }}">
    {{ $labels[$status] ?? $status }}
</span>
