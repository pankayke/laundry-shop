<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#87CEEB">
    <title>@yield('title', 'GeloWash Laundry Shop')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col antialiased">
    {{-- Background: gradient with subtle SVG pattern --}}
    <div class="fixed inset-0 -z-10 bg-gradient-to-br from-sky-100 via-white to-sky-50">
        <svg class="absolute bottom-0 left-0 w-full opacity-10" viewBox="0 0 800 200" preserveAspectRatio="none">
            <path d="M0,120 Q200,40 400,100 T800,80 L800,200 L0,200Z" fill="#87CEEB"/>
        </svg>
    </div>

    <div class="flex-1 flex flex-col items-center justify-center px-4 py-8 sm:py-12">
        {{-- Logo --}}
        <a href="/" class="mb-6 flex items-center gap-2">
            <svg viewBox="0 0 48 48" class="w-12 h-12 drop-shadow-lg">
                <circle cx="24" cy="24" r="22" fill="#E0F6FF" stroke="#4682B4" stroke-width="2"/>
                <circle cx="24" cy="27" r="11" fill="none" stroke="#4682B4" stroke-width="2.5"/>
                <circle cx="24" cy="27" r="5" fill="#87CEEB"/>
                <rect x="14" y="8" width="20" height="10" rx="5" fill="#FFD700"/>
                <circle cx="19" cy="13" r="1.5" fill="#4682B4"/>
                <circle cx="24" cy="13" r="1.5" fill="#4682B4"/>
            </svg>
            <div>
                <span class="text-2xl font-bold"><span class="text-sky-400 italic">Gelo</span><span class="text-sky-700">Wash</span></span>
                <p class="text-xs text-gray-400 -mt-0.5">Laundry Shop</p>
            </div>
        </a>

        {{-- Card --}}
        <div class="w-full max-w-md bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/50 p-8">
            @yield('content')
        </div>

        {{-- Footer link --}}
        <p class="text-xs text-gray-400 mt-6">
            <svg class="inline w-3 h-3 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
            0960-720-4055 &middot; Purok 3, Brgy. San Isidro, General Santos City
        </p>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
