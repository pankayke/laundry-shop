@php
/**
 * Status timeline component – SVG 6-step pipeline.
 * Usage: @include('components.status-timeline', ['currentStatus' => $order->status])
 */
use App\Models\Order;

$stepMeta = [
    'pending_approval' => ['label' => 'Pending',    'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
    'received'         => ['label' => 'Received',   'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
    'washing'          => ['label' => 'Washing',    'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'],
    'drying'           => ['label' => 'Drying',     'icon' => 'M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z'],
    'folding'          => ['label' => 'Folding',    'icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4'],
    'ready_for_pickup' => ['label' => 'Ready',      'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
    'collected'        => ['label' => 'Collected',  'icon' => 'M5 13l4 4L19 7'],
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

// Exclude non-progression statuses from the timeline.
$excludeFromTimeline = ['cancelled'];
$steps = array_values(array_filter(array_keys($stepMeta), fn($s) => !in_array($s, $excludeFromTimeline, true)));

$currentIdx = array_search($currentStatusKey, $steps, true);
if ($currentIdx === false) {
    $currentIdx = -1;
}
@endphp

<div class="w-full overflow-x-auto">
    <div class="flex items-center justify-between min-w-[500px] px-2">
        @foreach($steps as $idx => $step)
            @php
                $meta = $stepMeta[$step];
                $isCompleted = $idx < $currentIdx;
                $isCurrent   = $idx === $currentIdx;
                $isPending   = $idx > $currentIdx;

                $circleClass = $isCompleted
                    ? 'bg-green-500 text-white shadow-lg shadow-green-200'
                    : ($isCurrent
                        ? 'bg-sky-500 text-white shadow-lg shadow-sky-200 ring-4 ring-sky-200'
                        : 'bg-gray-200 text-gray-400');

                $labelClass = $isCurrent ? 'text-sky-700 font-bold' : ($isCompleted ? 'text-green-600 font-medium' : 'text-gray-400');
            @endphp

            {{-- Step --}}
            <div class="flex flex-col items-center relative">
                <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $circleClass }} transition-all duration-300">
                    @if($isCompleted)
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13l4 4L19 7"/></svg>
                    @else
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $meta['icon'] }}"/></svg>
                    @endif
                </div>
                <span class="text-[10px] mt-1.5 {{ $labelClass }} whitespace-nowrap">{{ $meta['label'] }}</span>
                @if($isCurrent)
                    <span class="absolute -bottom-4 text-[8px] bg-sky-100 text-sky-600 px-1.5 py-0.5 rounded-full font-bold">CURRENT</span>
                @endif
            </div>

            {{-- Connector line --}}
            @if(!$loop->last)
                <div class="flex-1 h-1 mx-1 rounded-full {{ $idx < $currentIdx ? 'bg-green-400' : 'bg-gray-200' }} transition-all duration-300"></div>
            @endif
        @endforeach
    </div>
</div>
