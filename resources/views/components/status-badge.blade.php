@php
/**
 * Status badge component with SVG icon.
 * Usage: @include('components.status-badge', ['status' => $order->status])
 */
$statusConfig = [
    'pending_approval'=> ['label' => 'Pending Approval', 'bg' => 'bg-amber-100',  'text' => 'text-amber-700',  'icon' => '<path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
    'received'        => ['label' => 'Received',        'bg' => 'bg-blue-100',   'text' => 'text-blue-700',   'icon' => '<path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>'],
    'washing'         => ['label' => 'Washing',         'bg' => 'bg-cyan-100',   'text' => 'text-cyan-700',   'icon' => '<path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>'],
    'drying'          => ['label' => 'Drying',          'bg' => 'bg-amber-100',  'text' => 'text-amber-700',  'icon' => '<path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>'],
    'folding'         => ['label' => 'Folding',         'bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'icon' => '<path d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>'],
    'ready_for_pickup'=> ['label' => 'Ready for Pickup', 'bg' => 'bg-green-100',  'text' => 'text-green-700',  'icon' => '<path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
    'collected'       => ['label' => 'Collected',       'bg' => 'bg-gray-100',   'text' => 'text-gray-600',   'icon' => '<path d="M5 13l4 4L19 7"/>'],
    'cancelled'       => ['label' => 'Cancelled',       'bg' => 'bg-red-100',    'text' => 'text-red-600',    'icon' => '<path d="M6 18L18 6M6 6l12 12"/>'],
];

$cfg = $statusConfig[$status] ?? ['label' => ucfirst(str_replace('_', ' ', $status)), 'bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'icon' => '<circle cx="12" cy="12" r="3"/>'];
@endphp

<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $cfg['bg'] }} {{ $cfg['text'] }}">
    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">{!! $cfg['icon'] !!}</svg>
    {{ $cfg['label'] }}
</span>
