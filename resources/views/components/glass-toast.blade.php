{{-- Glass Toast Notification – auto-dismiss after 5 seconds --}}
@php
    $typeConfig = [
        'success' => ['bg' => 'bg-green-500/90', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
        'error'   => ['bg' => 'bg-red-500/90',   'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
        'info'    => ['bg' => 'bg-sky-500/90',    'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        'warning' => ['bg' => 'bg-amber-500/90',  'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
    ];
    $cfg = $typeConfig[$type ?? 'info'] ?? $typeConfig['info'];
@endphp

<div x-data="{ show: true }"
     x-init="setTimeout(() => show = false, 5000)"
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-x-8"
     x-transition:enter-end="opacity-100 translate-x-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 translate-x-0"
     x-transition:leave-end="opacity-0 translate-x-8"
     class="fixed top-6 right-6 z-[100] max-w-sm w-full pointer-events-auto">
    <div class="{{ $cfg['bg'] }} backdrop-blur-xl text-white px-5 py-4 rounded-2xl shadow-2xl border border-white/20 flex items-start gap-3">
        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
            <path d="{{ $cfg['icon'] }}"/>
        </svg>
        <p class="text-sm font-medium leading-relaxed flex-1">{{ $message }}</p>
        <button @click="show = false" class="p-0.5 hover:bg-white/20 rounded-lg transition flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
</div>
