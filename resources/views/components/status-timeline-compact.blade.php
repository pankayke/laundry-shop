@php
/**
 * Compact horizontal status timeline for desktop order cards.
 * Usage: @include('components.status-timeline-compact', ['currentStatus' => $order->status])
 */
use App\Models\Order;

$stepLabels = [
    'pending_approval' => 'Pending',
    'received'         => 'Received',
    'washing'          => 'Washing',
    'drying'           => 'Drying',
    'folding'          => 'Folding',
    'ready_for_pickup' => 'Ready',
    'collected'        => 'Done',
];

// Build robust status aliases from both status keys and labels.
$statusAliases = [];
foreach (Order::STATUSES as $statusKey => $statusLabel) {
    $normalizedKey = strtolower((string) $statusKey);
    $normalizedLabel = strtolower((string) $statusLabel);
    $statusAliases[$normalizedKey] = $normalizedKey;
    $statusAliases[$normalizedLabel] = $normalizedKey;
}

// Fallback aliases for legacy or mixed-case data.
$statusAliases['pending approval'] = 'pending_approval';
$statusAliases['ready for pickup'] = 'ready_for_pickup';

$rawStatus = strtolower(trim((string) ($currentStatus ?? '')));
$normalizedStatus = str_replace(['-', ' '], '_', $rawStatus);

$currentStatusKey = $statusAliases[$rawStatus]
    ?? $statusAliases[str_replace('_', ' ', $normalizedStatus)]
    ?? $statusAliases[$normalizedStatus]
    ?? $normalizedStatus;

$excludeFromTimeline = ['cancelled'];
$steps = array_values(array_filter(array_keys($stepLabels), fn($s) => !in_array($s, $excludeFromTimeline, true)));
$currentIdx = array_search($currentStatusKey, $steps, true);
if ($currentIdx === false) {
    $currentIdx = -1;
}
@endphp

<div class="flex items-center gap-0.5 w-full">
    @foreach($steps as $idx => $step)
        @php
            $isCompleted = $idx < $currentIdx;
            $isCurrent   = $idx === $currentIdx;
            $label       = $stepLabels[$step] ?? $step;

            $dotClass = $isCompleted
                ? 'bg-green-500'
                : ($isCurrent ? 'bg-sky-500 ring-2 ring-sky-200' : 'bg-gray-300');
        @endphp

        <div class="flex items-center {{ !$loop->last ? 'flex-1' : '' }}">
            <div class="flex flex-col items-center">
                <div class="w-2.5 h-2.5 rounded-full {{ $dotClass }} transition-all duration-300 shrink-0"></div>
                <span class="text-[8px] mt-0.5 {{ $isCurrent ? 'text-sky-600 font-bold' : ($isCompleted ? 'text-green-600' : 'text-gray-400') }} whitespace-nowrap leading-none">{{ $label }}</span>
            </div>
            @if(!$loop->last)
                <div class="flex-1 h-0.5 mx-0.5 rounded-full {{ $idx < $currentIdx ? 'bg-green-400' : 'bg-gray-200' }} transition-all duration-300"></div>
            @endif
        </div>
    @endforeach
</div>
