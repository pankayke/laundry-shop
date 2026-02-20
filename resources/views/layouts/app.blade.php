<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#87CEEB">
    <meta name="description" content="GeloWash Laundry Shop – affordable wash, dry & fold services in General Santos City.">
    <title>@yield('title', 'GeloWash Laundry Shop')</title>
    <link rel="manifest" href="/manifest.json">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="@yield('body-class', 'bg-gradient-to-b from-white to-sky-50') font-sans min-h-screen flex flex-col antialiased">
    @include('layouts.navigation')

    <main class="flex-1 pt-20 pb-10 px-4 sm:px-6 lg:px-8">
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="max-w-7xl mx-auto mb-4">
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-2xl flex items-center gap-3 shadow-sm" role="alert">
                    <svg class="w-5 h-5 shrink-0" viewBox="0 0 20 20" fill="currentColor"><circle cx="10" cy="10" r="9" fill="none" stroke="currentColor" stroke-width="1.5"/><path d="M6 10l3 3 5-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <span class="text-sm">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-7xl mx-auto mb-4">
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl flex items-center gap-3 shadow-sm" role="alert">
                    <svg class="w-5 h-5 shrink-0" viewBox="0 0 20 20" fill="currentColor"><circle cx="10" cy="10" r="9" fill="none" stroke="currentColor" stroke-width="1.5"/><path d="M7 7l6 6M13 7l-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    <span class="text-sm">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="max-w-7xl mx-auto mb-4">
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl shadow-sm">
                    <ul class="list-disc list-inside text-sm space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-gradient-to-r from-sky-600 to-sky-700 text-white py-8 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    {{-- Footer Logo --}}
                    <svg viewBox="0 0 40 40" class="w-8 h-8">
                        <circle cx="20" cy="20" r="18" fill="#E0F6FF" stroke="white" stroke-width="2"/>
                        <circle cx="20" cy="22" r="9" fill="none" stroke="#4682B4" stroke-width="2"/>
                        <circle cx="20" cy="22" r="4" fill="#87CEEB"/>
                        <rect x="12" y="8" width="16" height="8" rx="4" fill="#FFD700"/>
                    </svg>
                    <div>
                        <span class="font-bold text-sm"><span class="italic text-sky-200">Gelo</span>Wash</span>
                        <p class="text-sky-200 text-xs">Laundry Shop</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 text-sm text-sky-100">
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        0960-720-4055
                    </span>
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Purok 3, Brgy. San Isidro, GenSan
                    </span>
                </div>
            </div>
            <div class="border-t border-sky-500 mt-4 pt-4 text-center text-xs text-sky-200">
                &copy; {{ date('Y') }} GeloWash Laundry Shop. All rights reserved.
            </div>
        </div>
    </footer>

    @stack('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
