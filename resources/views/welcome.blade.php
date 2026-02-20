<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#87CEEB">
    <meta name="description" content="GeloWash Laundry Shop – affordable wash, dry & fold services in General Santos City.">
    <title>GeloWash Laundry Shop</title>
    <link rel="manifest" href="/manifest.json">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen antialiased">
    {{-- Hero Section --}}
    <div class="relative min-h-screen flex flex-col bg-gradient-to-br from-sky-50 via-white to-sky-100 overflow-hidden">
        {{-- Background SVG decoration --}}
        <div class="absolute inset-0 -z-10 overflow-hidden">
            {{-- Floating bubbles --}}
            <svg class="absolute top-10 left-10 w-20 h-20 text-sky-200 opacity-40 animate-bounce" style="animation-duration:4s" viewBox="0 0 40 40"><circle cx="20" cy="20" r="18" fill="currentColor"/></svg>
            <svg class="absolute top-40 right-20 w-12 h-12 text-sky-200 opacity-30 animate-bounce" style="animation-duration:3s" viewBox="0 0 40 40"><circle cx="20" cy="20" r="18" fill="currentColor"/></svg>
            <svg class="absolute bottom-40 left-1/4 w-16 h-16 text-sky-100 opacity-40 animate-bounce" style="animation-duration:5s" viewBox="0 0 40 40"><circle cx="20" cy="20" r="18" fill="currentColor"/></svg>
            {{-- Wave --}}
            <svg class="absolute bottom-0 left-0 w-full" viewBox="0 0 1440 200" preserveAspectRatio="none">
                <path d="M0,100 C320,180 440,20 720,100 C1000,180 1120,40 1440,100 L1440,200 L0,200Z" fill="#87CEEB" fill-opacity="0.15"/>
                <path d="M0,140 C280,60 560,180 840,120 C1120,60 1280,140 1440,120 L1440,200 L0,200Z" fill="#87CEEB" fill-opacity="0.1"/>
            </svg>
        </div>

        {{-- Navigation --}}
        <nav class="w-full py-4 px-6 lg:px-12 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2 group">
                <svg viewBox="0 0 40 40" class="w-9 h-9 drop-shadow transition-transform group-hover:scale-110">
                    <circle cx="20" cy="20" r="18" fill="#E0F6FF" stroke="#4682B4" stroke-width="2"/>
                    <circle cx="20" cy="22" r="9" fill="none" stroke="#4682B4" stroke-width="2"/>
                    <circle cx="20" cy="22" r="4" fill="#87CEEB"/>
                    <rect x="12" y="8" width="16" height="8" rx="4" fill="#FFD700"/>
                    <circle cx="17" cy="12" r="1.2" fill="#4682B4"/>
                    <circle cx="21" cy="12" r="1.2" fill="#4682B4"/>
                </svg>
                <span class="text-lg font-bold">
                    <span class="text-sky-400 italic">Gelo</span><span class="text-sky-700">Wash</span>
                </span>
            </a>
            <div class="flex items-center gap-3">
                @auth
                    @php
                        $dashRoute = match(auth()->user()->role) {
                            'admin' => route('admin.dashboard'),
                            'staff' => route('staff.dashboard'),
                            default => route('customer.dashboard'),
                        };
                    @endphp
                    <a href="{{ $dashRoute }}" class="bg-gradient-to-r from-sky-500 to-sky-600 text-white px-5 py-2 rounded-xl text-sm font-semibold hover:from-sky-600 hover:to-sky-700 transition shadow-lg shadow-sky-200 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1"/></svg>
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('track.order') }}" class="text-sm text-gray-500 hover:text-sky-600 transition font-medium">Track Order</a>
                    <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-sky-600 transition font-medium">Login</a>
                    <a href="{{ route('register') }}" class="bg-gradient-to-r from-sky-500 to-sky-600 text-white px-5 py-2 rounded-xl text-sm font-semibold hover:from-sky-600 hover:to-sky-700 transition shadow-lg shadow-sky-200">Register</a>
                @endauth
            </div>
        </nav>

        {{-- Main Content --}}
        <div class="flex-1 flex items-center justify-center px-6 py-12">
            <div class="max-w-6xl mx-auto grid lg:grid-cols-2 gap-12 items-center">
                {{-- Left: Text --}}
                <div class="space-y-6">
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-800 leading-tight">
                        Fresh & Clean
                        <span class="block text-sky-500">Laundry Service</span>
                    </h1>
                    <p class="text-lg text-gray-500 max-w-md">
                        Affordable wash, dry & fold services in General Santos City. Track your laundry in real-time with our digital system.
                    </p>

                    {{-- Service Cards --}}
                    <div class="flex gap-4 flex-wrap">
                        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl px-5 py-4 flex items-center gap-3 border border-white/50 hover:shadow-2xl transition-all">
                            <svg class="w-10 h-10" viewBox="0 0 40 40" fill="none">
                                <circle cx="20" cy="20" r="18" fill="#E0F6FF" stroke="#4682B4" stroke-width="1.5"/>
                                <circle cx="20" cy="22" r="9" fill="none" stroke="#4682B4" stroke-width="1.5"/>
                                <circle cx="20" cy="22" r="4" fill="#87CEEB"/>
                                <rect x="12" y="8" width="16" height="8" rx="4" fill="#FFD700"/>
                            </svg>
                            <div>
                                <p class="font-bold text-gray-700 text-sm">Wash</p>
                                <p class="text-sky-600 font-bold text-sm">₱25/kg</p>
                            </div>
                        </div>
                        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl px-5 py-4 flex items-center gap-3 border border-white/50 hover:shadow-2xl transition-all">
                            <svg class="w-10 h-10" viewBox="0 0 40 40" fill="none">
                                <circle cx="20" cy="20" r="5" fill="#FFD700"/>
                                <path d="M20 6v3m0 22v3m14-14h-3M6 20H3m23.485 10.485l-2.121-2.121M8.636 8.636l-2.121-2.121m24.97 0l-2.12 2.121M8.635 31.364l-2.12 2.121" stroke="#d97706" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            <div>
                                <p class="font-bold text-gray-700 text-sm">Dry</p>
                                <p class="text-amber-600 font-bold text-sm">₱15/kg</p>
                            </div>
                        </div>
                        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl px-5 py-4 flex items-center gap-3 border border-white/50 hover:shadow-2xl transition-all">
                            <svg class="w-10 h-10" viewBox="0 0 40 40" fill="none">
                                <path d="M10 14h20M10 14a4 4 0 110-8h20a4 4 0 110 8M10 14v16a4 4 0 004 4h12a4 4 0 004-4V14m-14 8h8" stroke="#7c3aed" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            <div>
                                <p class="font-bold text-gray-700 text-sm">Fold</p>
                                <p class="text-purple-600 font-bold text-sm">₱10/kg</p>
                            </div>
                        </div>
                    </div>

                    {{-- CTA --}}
                    <div class="flex gap-3 pt-2">
                        <a href="{{ route('register') }}" class="bg-gradient-to-r from-sky-500 to-sky-600 text-white px-8 py-3 rounded-xl font-semibold hover:from-sky-600 hover:to-sky-700 transition shadow-lg shadow-sky-200 min-h-[44px] flex items-center gap-2">
                            Get Started
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                        <a href="{{ route('track.order') }}" class="border-2 border-sky-400 text-sky-600 px-6 py-3 rounded-xl font-semibold hover:bg-sky-50 transition min-h-[44px] flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            Track Order
                        </a>
                    </div>
                </div>

                {{-- Right: SVG Laundry Shop Building --}}
                <div class="flex justify-center lg:justify-end">
                    <svg viewBox="0 0 400 360" class="w-full max-w-[400px] drop-shadow-2xl">
                        {{-- Sky background --}}
                        <rect width="400" height="360" rx="20" fill="#E0F6FF"/>

                        {{-- Building --}}
                        <rect x="60" y="80" width="280" height="220" rx="8" fill="#87CEEB" stroke="#4682B4" stroke-width="2"/>
                        {{-- Roof --}}
                        <path d="M40 85 L200 20 L360 85Z" fill="#4682B4"/>
                        {{-- Sign --}}
                        <rect x="120" y="40" width="160" height="30" rx="6" fill="#FFD700"/>
                        <text x="200" y="62" text-anchor="middle" font-size="14" font-weight="bold" fill="#4682B4">GeloWash</text>

                        {{-- Windows / Washing Machines --}}
                        @for($i = 0; $i < 3; $i++)
                            @php $mx = 105 + ($i * 95); @endphp
                            <rect x="{{ $mx - 25 }}" y="110" width="50" height="55" rx="6" fill="white" stroke="#4682B4" stroke-width="1.5"/>
                            <circle cx="{{ $mx }}" cy="140" r="16" fill="none" stroke="#4682B4" stroke-width="1.5"/>
                            <circle cx="{{ $mx }}" cy="140" r="7" fill="#87CEEB" opacity="0.5"/>
                            <rect x="{{ $mx - 15 }}" y="113" width="30" height="8" rx="3" fill="#FFD700"/>
                            <circle cx="{{ $mx - 7 }}" cy="117" r="1.5" fill="#4682B4"/>
                            <circle cx="{{ $mx }}" cy="117" r="1.5" fill="#4682B4"/>
                        @endfor

                        {{-- Door --}}
                        <rect x="165" y="200" width="70" height="100" rx="6" fill="white" stroke="#4682B4" stroke-width="1.5"/>
                        <rect x="175" y="210" width="50" height="40" rx="3" fill="#E0F6FF"/>
                        <circle cx="225" cy="260" r="3" fill="#FFD700"/>

                        {{-- Hanger icon above door --}}
                        <path d="M188 190 L200 178 L212 190" fill="none" stroke="#4682B4" stroke-width="2" stroke-linecap="round"/>
                        <line x1="200" y1="175" x2="200" y2="170" stroke="#4682B4" stroke-width="2"/>
                        <circle cx="200" cy="168" r="3" fill="none" stroke="#4682B4" stroke-width="1.5"/>

                        {{-- Decorative elements --}}
                        <circle cx="50" cy="50" r="15" fill="#FFD700" opacity="0.3"/>
                        <circle cx="370" cy="140" r="10" fill="#87CEEB" opacity="0.3"/>
                        <path d="M310 250 Q320 235 330 250 Q340 265 350 250" fill="none" stroke="#4682B4" stroke-width="1.5" opacity="0.4"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Features --}}
        <div class="py-12 px-6">
            <div class="max-w-6xl mx-auto grid sm:grid-cols-3 gap-6">
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl p-6 text-center hover:shadow-2xl transition-all">
                    <svg class="w-12 h-12 mx-auto mb-3" viewBox="0 0 48 48" fill="none">
                        <circle cx="24" cy="24" r="22" fill="#E0F6FF" stroke="#4682B4" stroke-width="1.5"/>
                        <circle cx="24" cy="26" r="10" fill="none" stroke="#4682B4" stroke-width="2"/>
                        <circle cx="24" cy="26" r="4" fill="#87CEEB"/>
                        <rect x="14" y="8" width="20" height="8" rx="4" fill="#FFD700"/>
                    </svg>
                    <h3 class="font-bold text-gray-700 mb-1">Quality Service</h3>
                    <p class="text-sm text-gray-400">Professional wash, dry & fold with care</p>
                </div>
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl p-6 text-center hover:shadow-2xl transition-all">
                    <svg class="w-12 h-12 mx-auto mb-3" viewBox="0 0 48 48" fill="none" stroke="#4682B4" stroke-width="2">
                        <path d="M24 4v2m0 36v2m20-20h-2M4 24H2m33.799 14.142l-1.414-1.414M11.615 11.615l-1.414-1.414m28.384 0l-1.414 1.414M11.615 36.728l-1.414 1.414"/>
                        <circle cx="24" cy="24" r="8" fill="#FFD700" stroke="none"/>
                    </svg>
                    <h3 class="font-bold text-gray-700 mb-1">Real-Time Tracking</h3>
                    <p class="text-sm text-gray-400">Know exactly where your laundry is</p>
                </div>
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl p-6 text-center hover:shadow-2xl transition-all">
                    <svg class="w-12 h-12 mx-auto mb-3" viewBox="0 0 48 48" fill="none">
                        <circle cx="24" cy="24" r="20" fill="#E0F6FF" stroke="#4682B4" stroke-width="1.5"/>
                        <text x="24" y="30" text-anchor="middle" font-size="20" font-weight="bold" fill="#4682B4">₱</text>
                    </svg>
                    <h3 class="font-bold text-gray-700 mb-1">Affordable Prices</h3>
                    <p class="text-sm text-gray-400">Starting at just ₱10/kg</p>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <footer class="bg-gradient-to-r from-sky-600 to-sky-700 text-white py-6 mt-auto">
            <div class="max-w-6xl mx-auto px-6 text-center">
                <div class="flex items-center justify-center gap-2 mb-2">
                    <svg viewBox="0 0 28 28" class="w-6 h-6">
                        <circle cx="14" cy="14" r="12" fill="#E0F6FF" stroke="white" stroke-width="1.5"/>
                        <circle cx="14" cy="15" r="6" fill="none" stroke="#4682B4" stroke-width="1.5"/>
                        <circle cx="14" cy="15" r="2.5" fill="#87CEEB"/>
                        <rect x="8" y="5" width="12" height="5" rx="2.5" fill="#FFD700"/>
                    </svg>
                    <span class="font-bold text-sm"><span class="italic text-sky-200">Gelo</span>Wash</span>
                </div>
                <p class="text-sky-200 text-xs">0960-720-4055 · Purok 3, Brgy. San Isidro, General Santos City</p>
                <p class="text-sky-300 text-xs mt-2">&copy; {{ date('Y') }} GeloWash Laundry Shop. All rights reserved.</p>
            </div>
        </footer>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
