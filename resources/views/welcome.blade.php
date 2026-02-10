<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laundry Shop') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen font-sans text-gray-900 antialiased">
    <div class="relative overflow-hidden bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 text-white">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 text-9xl">&#x1F9FA;</div>
            <div class="absolute bottom-10 right-10 text-9xl">&#x1F455;</div>
        </div>
        <nav class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
            <span class="text-xl font-bold tracking-tight">&#x1F9FA; Laundry Shop</span>
            <div class="flex items-center gap-3">
                @auth
                    @php $dash = match(auth()->user()->role) { 'admin' => 'admin.dashboard', 'staff' => 'staff.dashboard', default => 'customer.dashboard' }; @endphp
                    <a href="{{ route($dash) }}" class="rounded-lg bg-white/20 px-4 py-2 text-sm font-medium hover:bg-white/30 transition">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium hover:text-white/80 transition">Login</a>
                    <a href="{{ route('register') }}" class="rounded-lg bg-white px-4 py-2 text-sm font-medium text-indigo-700 hover:bg-indigo-50 transition">Register</a>
                @endauth
            </div>
        </nav>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-24 text-center">
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight">Fresh & Clean<br>Laundry Service</h1>
            <p class="mt-6 max-w-2xl mx-auto text-lg text-indigo-100">Professional laundry care with real-time order tracking. Drop off your clothes, track the progress, and pick them up fresh!</p>
            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('register') }}" class="w-full sm:w-auto rounded-lg bg-white px-8 py-3 text-base font-semibold text-indigo-700 shadow-lg hover:bg-indigo-50 transition">Get Started</a>
                <a href="{{ route('track.order') }}" class="w-full sm:w-auto rounded-lg border-2 border-white/40 px-8 py-3 text-base font-semibold text-white hover:bg-white/10 transition">Track Your Order</a>
            </div>
        </div>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <h2 class="text-center text-3xl font-bold text-gray-900 mb-12">How It Works</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 text-center">
                <div class="text-4xl mb-4">&#x1F4E5;</div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Drop Off</h3>
                <p class="text-sm text-gray-500">Bring your laundry to our shop. We will weigh, sort, and give you a digital ticket.</p>
            </div>
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 text-center">
                <div class="text-4xl mb-4">&#x1F4F1;</div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Track Progress</h3>
                <p class="text-sm text-gray-500">Follow your laundry journey from washing to folding in real-time.</p>
            </div>
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 text-center">
                <div class="text-4xl mb-4">&#x2705;</div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Pick Up</h3>
                <p class="text-sm text-gray-500">Get notified when it is ready. Pick up your fresh, clean clothes!</p>
            </div>
        </div>
    </div>
    <div class="bg-white border-y border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <h2 class="text-center text-3xl font-bold text-gray-900 mb-12">Simple Pricing</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 max-w-3xl mx-auto">
                <div class="rounded-2xl bg-blue-50 p-8 text-center border border-blue-100">
                    <div class="text-3xl mb-2">&#x1FAE7;</div>
                    <h3 class="text-lg font-semibold text-gray-900">Wash</h3>
                    <p class="mt-2 text-3xl font-bold text-blue-600">&#x20B1;25<span class="text-sm font-normal text-gray-500">/kg</span></p>
                </div>
                <div class="rounded-2xl bg-cyan-50 p-8 text-center border border-cyan-100">
                    <div class="text-3xl mb-2">&#x2600;&#xFE0F;</div>
                    <h3 class="text-lg font-semibold text-gray-900">Dry</h3>
                    <p class="mt-2 text-3xl font-bold text-cyan-600">&#x20B1;15<span class="text-sm font-normal text-gray-500">/kg</span></p>
                </div>
                <div class="rounded-2xl bg-purple-50 p-8 text-center border border-purple-100">
                    <div class="text-3xl mb-2">&#x1F455;</div>
                    <h3 class="text-lg font-semibold text-gray-900">Fold</h3>
                    <p class="mt-2 text-3xl font-bold text-purple-600">&#x20B1;10<span class="text-sm font-normal text-gray-500">/kg</span></p>
                </div>
            </div>
        </div>
    </div>
    <footer class="py-8 text-center text-sm text-gray-400">&copy; {{ date('Y') }} Laundry Shop. All rights reserved.</footer>
</body>
</html>
