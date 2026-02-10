{{--
    Status timeline component.
    Usage: @include('components.status-timeline', ['currentStep' => $order->status_step])
--}}
@php
$steps = [
    ['label' => 'Received',  'icon' => '📥'],
    ['label' => 'Washing',   'icon' => '🫧'],
    ['label' => 'Drying',    'icon' => '☀️'],
    ['label' => 'Folding',   'icon' => '👕'],
    ['label' => 'Ready',     'icon' => '✅'],
    ['label' => 'Collected', 'icon' => '🏠'],
];
@endphp

<div class="flex items-center justify-between w-full overflow-x-auto pb-2">
    @foreach ($steps as $index => $step)
        @php $stepNum = $index + 1; @endphp
        <div class="flex flex-col items-center min-w-[60px] {{ $stepNum <= $currentStep ? 'text-indigo-600' : 'text-gray-300' }}">
            <div class="flex items-center justify-center w-10 h-10 rounded-full text-lg
                {{ $stepNum <= $currentStep ? 'bg-indigo-100' : 'bg-gray-100' }}">
                {{ $step['icon'] }}
            </div>
            <span class="mt-1 text-[10px] sm:text-xs font-medium text-center leading-tight">{{ $step['label'] }}</span>
        </div>

        @if (! $loop->last)
            <div class="flex-1 h-0.5 mx-1 {{ $stepNum < $currentStep ? 'bg-indigo-400' : 'bg-gray-200' }}"></div>
        @endif
    @endforeach
</div>
