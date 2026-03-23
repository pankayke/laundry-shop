{{--
    GeloWash Customer Dashboard — Responsive Production
    MOBILE  (<1024px): Hero header, single-column cards, FAB, bottom-sheet modal
    DESKTOP (≥1024px): v7.0 3-Pane — Fixed Sidebar (w-64) | Main Content | Right Utility Pane (w-80)
    Stack: Laravel 12, Tailwind CSS 4, Alpine.js 3
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#87CEEB">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>Dashboard — GeloWash Laundry Shop</title>
    <link rel="manifest" href="/manifest.json">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50/80 to-indigo-50 min-h-screen font-sans antialiased"
      x-data="customerDashboard()" x-cloak>

{{-- ╔══════════════════════════════════════════════════════════════════╗
     ║  SECTION A — DESKTOP LAYOUT (hidden below lg:)                  ║
     ╚══════════════════════════════════════════════════════════════════╝ --}}

{{-- ── A1: Fixed Left Sidebar (w-64) ──────────────────────────────── --}}
<aside class="fixed left-0 top-0 h-screen w-64 bg-white/40 backdrop-blur-xl border-r border-white/50 z-40 shadow-2xl hidden lg:flex lg:flex-col"
       aria-label="Sidebar navigation">

    {{-- Brand --}}
    <div class="px-6 py-6 border-b border-white/30">
        <a href="/" class="flex items-center gap-3 group">
            <div class="w-11 h-11 bg-gradient-to-br from-[#87CEEB] to-[#FFD700] rounded-2xl flex items-center justify-center shadow-lg shadow-sky-200/40 transition-transform group-hover:scale-105">
                <svg viewBox="0 0 24 24" class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="14" r="7"/><circle cx="12" cy="14" r="3"/>
                    <path d="M7 4h10a2 2 0 012 2v2H5V6a2 2 0 012-2z"/>
                    <circle cx="9" cy="6" r="0.8" fill="currentColor"/><circle cx="12" cy="6" r="0.8" fill="currentColor"/>
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-black tracking-tight">
                    <span class="bg-gradient-to-r from-[#87CEEB] to-[#4682B4] bg-clip-text text-transparent italic">Gelo</span><span class="bg-gradient-to-r from-[#4682B4] to-[#FFD700] bg-clip-text text-transparent">Wash</span>
                </h1>
                <p class="text-[10px] text-[#4682B4]/70 font-medium tracking-widest uppercase -mt-0.5">Laundry Shop</p>
            </div>
        </a>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-3 mb-2">Menu</p>

        <a href="{{ route('customer.dashboard') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition-all duration-200
                  {{ request()->routeIs('customer.dashboard') ? 'bg-[#87CEEB]/15 text-[#4682B4] border-r-[3px] border-[#87CEEB] shadow-sm' : 'text-gray-500 hover:bg-white/40 hover:text-[#4682B4]' }}">
            <div class="w-8 h-8 rounded-xl {{ request()->routeIs('customer.dashboard') ? 'bg-[#87CEEB]/20' : 'bg-gray-100/60' }} flex items-center justify-center transition-colors">
                <svg class="w-[18px] h-[18px] {{ request()->routeIs('customer.dashboard') ? 'text-[#87CEEB]' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1"/>
                </svg>
            </div>
            Dashboard
        </a>

        <a href="{{ route('customer.dashboard') }}#my-orders"
           @click.prevent="scrollToOrders()"
           class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-medium text-gray-500 hover:bg-white/40 hover:text-[#4682B4] transition-all duration-200">
            <div class="w-8 h-8 rounded-xl bg-gray-100/60 flex items-center justify-center">
                <svg class="w-[18px] h-[18px] text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            My Orders
            @if($activeOrders->count())
                <span class="ml-auto text-[10px] font-bold bg-[#87CEEB]/20 text-[#4682B4] px-2 py-0.5 rounded-full">{{ $activeOrders->count() }}</span>
            @endif
        </a>

        <a href="{{ route('track.order') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-medium text-gray-500 hover:bg-white/40 hover:text-[#4682B4] transition-all duration-200">
            <div class="w-8 h-8 rounded-xl bg-gray-100/60 flex items-center justify-center">
                <svg class="w-[18px] h-[18px] text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            Track Order
        </a>

        <div class="!mt-6">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-3 mb-2">Account</p>
        </div>

        <span class="flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-medium text-gray-400 cursor-default">
            <div class="w-8 h-8 rounded-xl bg-gray-100/60 flex items-center justify-center">
                <svg class="w-[18px] h-[18px] text-gray-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            Settings
        </span>
    </nav>

    {{-- User Card --}}
    <div class="px-4 py-4 border-t border-white/30">
        <div class="flex items-center gap-3 px-3 py-3 rounded-2xl bg-white/30 backdrop-blur-sm">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#87CEEB] to-sky-600 flex items-center justify-center shadow-md shadow-sky-200/30 shrink-0">
                <span class="text-sm font-black text-white">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-bold text-gray-800 truncate">{{ Auth::user()->name }}</p>
                <p class="text-[10px] text-gray-400 truncate">{{ Auth::user()->email }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="p-2 rounded-xl text-gray-400 hover:text-red-500 hover:bg-red-50/60 transition-all duration-200" title="Sign out">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </form>
        </div>
    </div>
</aside>

{{-- ── A2: Desktop Control Center Header ──────────────────────────── --}}
<header class="fixed top-0 left-64 xl:right-80 right-0 h-16 bg-white/60 backdrop-blur-xl border-b border-white/50 z-30 shadow-sm hidden lg:block">
    <div class="h-full px-8 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-br from-[#87CEEB] to-sky-600 rounded-full flex items-center justify-center text-white font-bold shadow-lg shadow-sky-200/30 shrink-0">
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
            <div>
                <p class="text-sm font-bold text-[#4682B4]">Welcome back,</p>
                <p class="text-xs text-gray-500 -mt-0.5">{{ Auth::user()->name }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2.5">
            <a href="{{ route('track.order') }}"
               class="inline-flex items-center gap-1.5 px-5 py-2 text-xs font-bold text-[#4682B4] border border-[#87CEEB]/40 rounded-2xl backdrop-blur-sm hover:bg-[#87CEEB]/10 hover:shadow-md hover:border-[#87CEEB]/60 transition-all duration-200 active:scale-[0.98]">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Track Order
            </a>
            <button @click="showRequestModal = true"
                    class="inline-flex items-center gap-1.5 px-5 py-2 text-xs font-bold text-white bg-gradient-to-r from-[#87CEEB] to-sky-600 rounded-2xl shadow-lg shadow-sky-200/40 hover:shadow-xl hover:-translate-y-0.5 active:scale-[0.98] focus:ring-4 focus:ring-sky-200/50 focus:outline-none transition-all duration-200">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
                Submit Request
            </button>
        </div>
    </div>
</header>

<div class="hidden lg:flex lg:ml-64 lg:items-start pt-16 min-h-screen">

{{-- ── A3: Desktop Right Utility Pane (w-80, sticky on xl+) ───────── --}}
<aside class="hidden xl:block w-80 shrink-0 p-5 order-last" aria-label="Utility pane">
    <div class="space-y-5 xl:sticky xl:top-20 xl:self-start xl:max-h-[calc(100vh-5rem)] xl:overflow-y-auto xl:z-10">

    {{-- Shop Details --}}
    <div class="bg-white/50 backdrop-blur-xl rounded-2xl border border-white/50 shadow-lg p-5">
        <h4 class="text-sm font-bold text-[#4682B4] mb-3 flex items-center gap-2">
            <svg class="w-4 h-4 text-[#87CEEB]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            Shop Details
        </h4>
        <div class="space-y-2.5 text-xs">
            @if($settings->shop_address)
            <div class="flex items-start gap-2.5">
                <svg class="w-4 h-4 text-gray-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span class="text-gray-600 leading-relaxed">{{ $settings->shop_address }}</span>
            </div>
            @endif
            @if($settings->shop_phone)
            <div class="flex items-center gap-2.5">
                <svg class="w-4 h-4 text-green-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                <span class="text-gray-600 font-medium">{{ $settings->shop_phone }}</span>
            </div>
            @endif
            <div class="flex items-center gap-2.5">
                <svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-gray-600">Mon – Sat • 7AM – 8PM</span>
            </div>
        </div>
    </div>

    {{-- GCash QR --}}
    <div class="bg-white/50 backdrop-blur-xl rounded-2xl border border-white/50 shadow-lg p-5">
        <h4 class="text-sm font-bold text-[#4682B4] mb-3 flex items-center gap-2">
            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            Pay Online
        </h4>
        @if($settings->qr_code_path)
            <div class="w-full aspect-square bg-white rounded-xl flex items-center justify-center p-3 mb-3 border border-gray-200/50">
                <img src="{{ asset('storage/' . $settings->qr_code_path) }}" alt="GCash QR Code" class="w-full h-full object-contain">
            </div>
        @else
            <div class="w-full h-40 bg-gray-50 rounded-xl flex flex-col items-center justify-center mb-3 border-2 border-dashed border-gray-200">
                <svg class="w-8 h-8 text-gray-300 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                <p class="text-[10px] text-gray-400">QR Code not set</p>
            </div>
        @endif
        @if($settings->gcash_number)
            <div class="flex items-center justify-between bg-blue-50/60 rounded-xl px-3 py-2.5 border border-blue-100/40">
                <div>
                    <p class="text-[10px] text-gray-500">Pay to:</p>
                    <p class="text-sm font-bold text-[#4682B4]">{{ $settings->gcash_number }}</p>
                </div>
                <button onclick="navigator.clipboard.writeText('{{ $settings->gcash_number }}'); this.innerHTML='<svg class=\'w-4 h-4 text-green-500\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'2\' viewBox=\'0 0 24 24\'><path d=\'M5 13l4 4L19 7\'/></svg>'; setTimeout(() => this.innerHTML='<svg class=\'w-4 h-4\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'2\' viewBox=\'0 0 24 24\'><path d=\'M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3\'/></svg>', 2000)"
                        class="p-2 rounded-lg hover:bg-blue-100/60 transition" title="Copy number">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                </button>
            </div>
        @endif
    </div>

    {{-- Pricing Table --}}
    <div class="bg-white/50 backdrop-blur-xl rounded-2xl border border-white/50 shadow-lg p-5">
        <h4 class="text-sm font-bold text-[#4682B4] mb-3 flex items-center gap-2">
            <svg class="w-4 h-4 text-[#FFD700]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
            Service Rates
        </h4>
        <div class="space-y-2">
            <div class="flex items-center justify-between px-3 py-2.5 rounded-xl bg-sky-50/50 border border-sky-100/40">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#87CEEB] shadow-sm shadow-sky-200"></span>
                    <span class="text-xs font-medium text-gray-600">Wash</span>
                </div>
                <span class="text-sm font-bold text-[#87CEEB]">₱{{ number_format($settings->wash_price, 2) }}/kg</span>
            </div>
            <div class="flex items-center justify-between px-3 py-2.5 rounded-xl bg-amber-50/50 border border-amber-100/40">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-400 shadow-sm shadow-amber-200"></span>
                    <span class="text-xs font-medium text-gray-600">Dry</span>
                </div>
                <span class="text-sm font-bold text-[#87CEEB]">₱{{ number_format($settings->dry_price, 2) }}/kg</span>
            </div>
            <div class="flex items-center justify-between px-3 py-2.5 rounded-xl bg-purple-50/50 border border-purple-100/40">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-purple-400 shadow-sm shadow-purple-200"></span>
                    <span class="text-xs font-medium text-gray-600">Fold</span>
                </div>
                <span class="text-sm font-bold text-[#87CEEB]">₱{{ number_format($settings->fold_price, 2) }}/kg</span>
            </div>
        </div>
    </div>
</div>
</aside>

{{-- ── A4: Desktop Main Content (lg:ml-64 xl:mr-80) ──────────────── --}}
<main class="hidden lg:block flex-1 min-w-0">
    <div class="p-8 space-y-8">

        {{-- Flash Messages (desktop) --}}
        @if(session('success'))
            <div class="bg-green-50/80 backdrop-blur border border-green-200/60 text-green-700 px-4 py-3 rounded-2xl flex items-center gap-3 shadow-sm" role="alert">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50/80 backdrop-blur border border-red-200/60 text-red-700 px-4 py-3 rounded-2xl flex items-center gap-3 shadow-sm" role="alert">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-50/80 backdrop-blur border border-red-200/60 text-red-700 px-4 py-3 rounded-2xl shadow-sm">
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Stats Row (4-column) --}}
        <section>
            <div class="grid grid-cols-4 gap-6">
                <div class="bg-white/40 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl p-5 flex items-start justify-between group hover:shadow-2xl hover:border-[#87CEEB]/30 transition-all duration-300">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Active</p>
                        <p class="text-3xl font-black text-[#87CEEB] mt-1 leading-none">{{ $activeOrders->count() }}</p>
                        <p class="text-[10px] text-gray-400 mt-1.5">orders in progress</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-[#87CEEB]/15 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-[#87CEEB]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                </div>
                <div class="bg-white/40 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl p-5 flex items-start justify-between group hover:shadow-2xl hover:border-[#FFD700]/30 transition-all duration-300">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Pending</p>
                        <p class="text-3xl font-black text-[#FFD700] mt-1 leading-none">{{ $activeOrders->where('status', 'pending_approval')->count() }}</p>
                        <p class="text-[10px] text-gray-400 mt-1.5">awaiting approval</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-[#FFD700]/15 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-[#FFD700]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="bg-white/40 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl p-5 flex items-start justify-between group hover:shadow-2xl hover:border-emerald-300/30 transition-all duration-300">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Ready</p>
                        <p class="text-3xl font-black text-emerald-500 mt-1 leading-none">{{ $activeOrders->where('status', 'ready_for_pickup')->count() }}</p>
                        <p class="text-[10px] text-gray-400 mt-1.5">ready for pickup</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/15 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="bg-white/40 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl p-5 flex items-start justify-between group hover:shadow-2xl hover:border-purple-300/30 transition-all duration-300">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Completed</p>
                        <p class="text-3xl font-black text-purple-500 mt-1 leading-none">{{ $pastOrders->where('status', 'collected')->count() }}</p>
                        <p class="text-[10px] text-gray-400 mt-1.5">total completed</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-purple-500/15 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                    </div>
                </div>
            </div>
        </section>

        {{-- Active Orders Grid (3-column) --}}
        <section id="my-orders" x-ref="desktopOrdersSection" class="scroll-mt-24">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-[#4682B4] flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#87CEEB]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Active Orders
                    @if($activeOrders->count())
                        <span class="text-[10px] font-bold bg-[#87CEEB]/15 text-[#4682B4] px-2.5 py-0.5 rounded-full">{{ $activeOrders->count() }}</span>
                    @endif
                </h3>
            </div>

            @if($activeOrders->isEmpty())
                <div class="bg-white/40 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl p-12 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gradient-to-br from-[#87CEEB]/20 to-sky-100/40 flex items-center justify-center">
                        <svg class="w-8 h-8 text-[#87CEEB]/60" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <p class="text-sm font-semibold text-gray-500">No active orders</p>
                    <p class="text-xs text-gray-400 mt-1 mb-5">Submit a new laundry request to get started.</p>
                    <button @click="showRequestModal = true"
                            class="inline-flex items-center gap-1.5 px-6 py-2.5 text-xs font-bold text-white bg-gradient-to-r from-[#87CEEB] to-sky-600 rounded-2xl shadow-lg shadow-sky-200/40 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
                        Submit Request
                    </button>
                </div>
            @else
                <div class="grid grid-cols-1 xl:grid-cols-2 2xl:grid-cols-3 gap-5">
                    @foreach($activeOrders as $order)
                    <div class="bg-white/40 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl p-5 flex flex-col hover:shadow-2xl hover:border-[#87CEEB]/30 transition-all duration-300 group">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-black text-[#4682B4] bg-[#87CEEB]/15 px-2.5 py-1 rounded-xl">#{{ $order->ticket_number }}</span>
                                <span class="text-[10px] text-gray-400 font-medium">{{ $order->created_at->format('M d') }}</span>
                            </div>
                            @include('components.status-badge', ['status' => $order->status])
                        </div>

                        <div class="mb-4 px-1">
                            @include('components.status-timeline-compact', ['currentStatus' => $order->status])
                        </div>

                        <div class="grid grid-cols-2 gap-2.5 mb-3">
                            <div class="bg-white/40 rounded-xl px-3 py-2.5 border border-white/60">
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Weight</p>
                                <p class="text-sm font-bold text-gray-700 mt-0.5">
                                    @if($order->total_weight && $order->total_weight > 0)
                                        {{ number_format($order->total_weight, 1) }} kg
                                    @elseif($order->estimated_weight)
                                        ~{{ number_format($order->estimated_weight, 1) }} kg
                                    @else
                                        —
                                    @endif
                                </p>
                            </div>
                            <div class="bg-white/40 rounded-xl px-3 py-2.5 border border-white/60">
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Total</p>
                                <p class="text-sm font-bold text-gray-700 mt-0.5">₱{{ number_format($order->total_price, 2) }}</p>
                            </div>
                            <div class="bg-white/40 rounded-xl px-3 py-2.5 border border-white/60">
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-0.5">Payment</p>
                                @include('components.payment-badge', ['order' => $order])
                            </div>
                            <div class="bg-white/40 rounded-xl px-3 py-2.5 border border-white/60">
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Services</p>
                                <div class="flex flex-wrap gap-1 mt-1">
                                    @if($order->requested_services)
                                        @foreach($order->requested_services as $svc)
                                            <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-md bg-[#87CEEB]/10 text-[#4682B4] border border-[#87CEEB]/20">{{ ucfirst($svc) }}</span>
                                        @endforeach
                                    @elseif($order->items->count())
                                        @foreach($order->items->pluck('service_type')->unique() as $svc)
                                            <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-md bg-[#87CEEB]/10 text-[#4682B4] border border-[#87CEEB]/20">{{ ucfirst($svc) }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-gray-400 text-[10px]">—</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($order->special_instructions || $order->notes)
                            <div class="text-[10px] text-gray-500 bg-amber-50/40 rounded-xl px-3 py-2 mb-2 border border-amber-100/30 leading-relaxed">
                                <span class="font-bold text-amber-600">Note:</span> {{ Str::limit($order->special_instructions ?? $order->notes, 60) }}
                            </div>
                        @endif

                        <div class="mt-auto pt-2 flex justify-end min-h-[28px]">
                            @if($order->status === 'pending_approval')
                                <form method="POST" action="{{ route('customer.orders.cancel', $order) }}"
                                      onsubmit="return confirm('Cancel order #{{ $order->ticket_number }}?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="text-[11px] font-semibold text-red-400 hover:text-red-600 transition-colors duration-200 opacity-0 group-hover:opacity-100 focus:opacity-100">
                                        Cancel Request
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </section>

        {{-- Order History Table --}}
        @if($pastOrders->isNotEmpty())
        <section>
            <h3 class="text-lg font-bold text-[#4682B4] mb-5 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Order History
            </h3>
            <div class="bg-white/40 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead>
                            <tr class="border-b border-white/40">
                                <th class="px-5 py-3.5 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Ticket</th>
                                <th class="px-5 py-3.5 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Date</th>
                                <th class="px-5 py-3.5 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Weight</th>
                                <th class="px-5 py-3.5 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total</th>
                                <th class="px-5 py-3.5 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Payment</th>
                                <th class="px-5 py-3.5 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/30">
                            @foreach($pastOrders as $order)
                            <tr class="hover:bg-white/30 transition-colors duration-150">
                                <td class="px-5 py-3 font-bold text-[#4682B4]">#{{ $order->ticket_number }}</td>
                                <td class="px-5 py-3 text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                                <td class="px-5 py-3 text-gray-600 font-medium">{{ $order->total_weight ? number_format($order->total_weight, 1) . ' kg' : '—' }}</td>
                                <td class="px-5 py-3 font-bold text-gray-700">₱{{ number_format($order->total_price, 2) }}</td>
                                <td class="px-5 py-3">@include('components.payment-badge', ['order' => $order])</td>
                                <td class="px-5 py-3">@include('components.status-badge', ['status' => $order->status])</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        @endif

    </div>
</main>
</div>


{{-- ╔══════════════════════════════════════════════════════════════════╗
     ║  SECTION B — MOBILE LAYOUT (hidden at lg: and above)           ║
     ╚══════════════════════════════════════════════════════════════════╝ --}}

{{-- ── B1: Mobile Hero Header (fixed top, gradient) ───────────────── --}}
<header class="fixed top-0 left-0 right-0 z-30 bg-gradient-to-r from-[#87CEEB] to-sky-400 shadow-lg lg:hidden">
    <div class="flex items-center justify-between px-5 py-4">
        <div class="min-w-0">
            <p class="text-white/70 text-xs font-medium">Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 18 ? 'afternoon' : 'evening') }},</p>
            <h1 class="text-white text-lg font-bold truncate leading-tight">{{ Auth::user()->name }}</h1>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('track.order') }}"
               class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center active:scale-95 transition-transform"
               aria-label="Track order">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
            <button @click="showMobileMenu = !showMobileMenu"
                    class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center text-white font-black text-base active:scale-95 transition-transform ring-2 ring-white/30"
                    aria-label="Menu">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </button>
        </div>
    </div>
    {{-- Quick Stats --}}
    <div class="flex items-center gap-4 px-5 pb-4 -mt-1">
        <div class="flex items-center gap-1.5">
            <span class="w-2 h-2 rounded-full bg-white/80 {{ $activeOrders->count() > 0 ? 'animate-pulse' : '' }}"></span>
            <span class="text-white/90 text-xs font-semibold">{{ $activeOrders->count() }} Active</span>
        </div>
        @if($activeOrders->where('status', 'ready_for_pickup')->count() > 0)
        <div class="flex items-center gap-1.5">
            <span class="w-2 h-2 rounded-full bg-emerald-300"></span>
            <span class="text-white/90 text-xs font-semibold">{{ $activeOrders->where('status', 'ready_for_pickup')->count() }} Ready</span>
        </div>
        @endif
        @if($activeOrders->where('status', 'pending_approval')->count() > 0)
        <div class="flex items-center gap-1.5">
            <span class="w-2 h-2 rounded-full bg-amber-300"></span>
            <span class="text-white/90 text-xs font-semibold">{{ $activeOrders->where('status', 'pending_approval')->count() }} Pending</span>
        </div>
        @endif
    </div>
</header>

{{-- ── B2: Mobile Dropdown Menu ───────────────────────────────────── --}}
<div x-show="showMobileMenu" x-cloak
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0 -translate-y-2"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0 -translate-y-2"
     @click.away="showMobileMenu = false"
     class="fixed top-[108px] right-4 z-40 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden lg:hidden">
    <div class="px-4 py-3 border-b border-gray-100">
        <p class="text-sm font-bold text-gray-800 truncate">{{ Auth::user()->name }}</p>
        <p class="text-[11px] text-gray-400 truncate">{{ Auth::user()->email }}</p>
    </div>
    <div class="py-1.5">
        <a href="{{ route('customer.dashboard') }}#my-orders"
           @click.prevent="scrollToOrders(); showMobileMenu = false"
           class="flex items-center gap-3 px-4 py-3 text-sm text-gray-600 active:bg-gray-50 transition-colors min-h-12">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            My Orders
        </a>
        <a href="{{ route('track.order') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-600 active:bg-gray-50 transition-colors min-h-12">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            Track Order
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm text-red-500 active:bg-red-50 transition-colors min-h-12">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Sign Out
            </button>
        </form>
    </div>
</div>

{{-- ── B3: Mobile Main Content ────────────────────────────────────── --}}
<main class="lg:hidden pt-[120px] pb-28 px-4">

    {{-- Flash Messages (mobile) --}}
    @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-2xl flex items-center gap-3 text-sm" role="alert">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl flex items-center gap-3 text-sm" role="alert">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl text-sm">
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Active Orders --}}
    @if($activeOrders->isEmpty())
        {{-- Empty State --}}
        <div class="flex flex-col items-center justify-center text-center py-24 px-6">
            <div class="w-32 h-32 mb-6 text-gray-200">
                <svg viewBox="0 0 120 120" fill="none" class="w-full h-full">
                    <circle cx="60" cy="65" r="40" stroke="currentColor" stroke-width="3" stroke-dasharray="6 4"/>
                    <circle cx="60" cy="65" r="20" fill="currentColor" opacity="0.15"/>
                    <rect x="35" y="20" width="50" height="20" rx="10" fill="currentColor" opacity="0.25"/>
                    <circle cx="48" cy="30" r="3" fill="currentColor" opacity="0.4"/>
                    <circle cx="60" cy="30" r="3" fill="currentColor" opacity="0.4"/>
                    <circle cx="72" cy="30" r="3" fill="currentColor" opacity="0.4"/>
                    <path d="M45 65 C50 55, 70 55, 75 65" stroke="currentColor" stroke-width="2" opacity="0.3"/>
                    <path d="M50 70 C55 62, 65 62, 70 70" stroke="currentColor" stroke-width="2" opacity="0.3"/>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-slate-700 mb-1">No laundry yet</h2>
            <p class="text-sm text-slate-400 mb-8">Tap <span class="font-bold text-[#87CEEB]">+</span> to submit your first request</p>
            <button @click="showRequestModal = true"
                    class="w-full max-w-xs bg-gradient-to-r from-[#87CEEB] to-sky-500 text-white font-bold text-base py-4 rounded-2xl shadow-lg shadow-sky-200/50 active:scale-[0.98] transition-transform min-h-12">
                + New Request
            </button>
        </div>
    @else
        <div x-ref="mobileOrdersSection" class="scroll-mt-[140px]"></div>

        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-bold text-slate-700">Active Orders</h2>
            <span class="text-xs font-bold text-[#87CEEB] bg-[#87CEEB]/10 px-2.5 py-1 rounded-full">{{ $activeOrders->count() }}</span>
        </div>

        <div class="space-y-4">
            @foreach($activeOrders as $order)
            <div class="bg-white/85 backdrop-blur-xl rounded-3xl border border-white/40 shadow-md p-5 flex flex-col">
                {{-- Ticket + Badge --}}
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <span class="text-2xl font-black bg-gradient-to-r from-[#87CEEB] to-[#4682B4] bg-clip-text text-transparent leading-none">
                            #{{ $order->ticket_number }}
                        </span>
                        <p class="text-[11px] text-slate-400 font-medium mt-0.5">{{ $order->created_at->format('M d, Y · g:i A') }}</p>
                    </div>
                    @include('components.status-badge', ['status' => $order->status])
                </div>

                {{-- Timeline --}}
                <div class="mb-4">
                    @include('components.status-timeline-compact', ['currentStatus' => $order->status])
                </div>

                {{-- Weight + Total --}}
                <div class="flex items-center gap-3 text-sm">
                    <div class="flex items-center gap-1.5 bg-slate-50 rounded-xl px-3 py-2 flex-1">
                        <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l3 9a5.002 5.002 0 01-6.001 0M18 7l-3 9m-3-9l-6-2m6 2l-6 2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <span class="font-bold text-slate-700">
                            @if($order->total_weight && $order->total_weight > 0)
                                {{ number_format($order->total_weight, 1) }} kg
                            @elseif($order->estimated_weight)
                                ~{{ number_format($order->estimated_weight, 1) }} kg
                            @else
                                —
                            @endif
                        </span>
                    </div>
                    <div class="flex items-center gap-1.5 bg-slate-50 rounded-xl px-3 py-2 flex-1">
                        <span class="text-slate-400 text-xs font-medium">₱</span>
                        <span class="font-bold text-slate-700">{{ number_format($order->total_price, 2) }}</span>
                    </div>
                </div>

                {{-- Services + Payment --}}
                <div class="flex items-center justify-between mt-3">
                    <div class="flex flex-wrap gap-1.5">
                        @if($order->requested_services)
                            @foreach($order->requested_services as $svc)
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-lg bg-[#87CEEB]/10 text-[#4682B4] border border-[#87CEEB]/20">{{ ucfirst($svc) }}</span>
                            @endforeach
                        @elseif($order->items->count())
                            @foreach($order->items->pluck('service_type')->unique() as $svc)
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-lg bg-[#87CEEB]/10 text-[#4682B4] border border-[#87CEEB]/20">{{ ucfirst($svc) }}</span>
                            @endforeach
                        @endif
                    </div>
                    <div class="shrink-0">
                        @include('components.payment-badge', ['order' => $order])
                    </div>
                </div>

                {{-- Notes --}}
                @if($order->special_instructions || $order->notes)
                    <div class="text-xs text-slate-500 bg-amber-50/60 rounded-xl px-3.5 py-2.5 mt-3 border border-amber-100/40 leading-relaxed">
                        <span class="font-bold text-amber-600">Note:</span> {{ Str::limit($order->special_instructions ?? $order->notes, 80) }}
                    </div>
                @endif

                {{-- Cancel — full-width, pending_approval only --}}
                @if($order->status === 'pending_approval')
                    <form method="POST" action="{{ route('customer.orders.cancel', $order) }}" class="mt-3"
                          onsubmit="return confirm('Cancel order #{{ $order->ticket_number }}?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                                class="w-full py-3 rounded-2xl text-sm font-bold text-red-500 bg-red-50 border border-red-100 active:bg-red-100 transition-colors min-h-12">
                            Cancel Request
                        </button>
                    </form>
                @endif
            </div>
            @endforeach
        </div>
    @endif

    {{-- Past Orders (mobile) --}}
    @if($pastOrders->isNotEmpty())
    <div class="mt-8">
        <h2 class="text-base font-bold text-slate-700 mb-4">Past Orders</h2>
        <div class="space-y-3">
            @foreach($pastOrders as $order)
            <div class="bg-white/70 backdrop-blur-sm rounded-2xl border border-white/40 shadow-sm px-4 py-3.5 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl {{ $order->status === 'collected' ? 'bg-green-50' : 'bg-red-50' }} flex items-center justify-center shrink-0">
                    @if($order->status === 'collected')
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                    @else
                        <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                    @endif
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-bold text-slate-700">#{{ $order->ticket_number }}</span>
                        @include('components.status-badge', ['status' => $order->status])
                    </div>
                    <p class="text-[11px] text-slate-400 mt-0.5">{{ $order->created_at->format('M d, Y') }} · {{ $order->total_weight ? number_format($order->total_weight, 1) . ' kg' : '—' }}</p>
                </div>
                <span class="text-sm font-bold text-slate-700 shrink-0">₱{{ number_format($order->total_price, 2) }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</main>

{{-- ── B4: Mobile FAB (64px) ──────────────────────────────────────── --}}
<button @click="showRequestModal = true"
        class="fixed bottom-8 right-6 z-40 w-16 h-16 bg-gradient-to-br from-[#87CEEB] to-sky-500 rounded-full shadow-2xl shadow-sky-300/50 flex items-center justify-center text-white active:scale-95 hover:scale-110 transition-transform lg:hidden"
        aria-label="New laundry request">
    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-linecap="round"/></svg>
</button>


{{-- ╔══════════════════════════════════════════════════════════════════╗
     ║  SECTION C — SHARED: Submit Request Modal                       ║
     ║  Desktop: centered overlay | Mobile: bottom-sheet               ║
     ╚══════════════════════════════════════════════════════════════════╝ --}}
<div x-show="showRequestModal" x-cloak
     class="fixed inset-0 z-50"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">

    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/30 backdrop-blur-sm" @click="showRequestModal = false"></div>

    {{-- Modal Container —
         Mobile: bottom-sheet (rounded-t-3xl, full-width at bottom)
         Desktop: centered card (max-w-lg, rounded-2xl) --}}
    <div x-show="showRequestModal"
         class="absolute
                bottom-0 left-0 right-0 lg:bottom-auto lg:left-1/2 lg:top-1/2 lg:-translate-x-1/2 lg:-translate-y-1/2
                bg-white lg:bg-white/90 lg:backdrop-blur-2xl
                rounded-t-3xl lg:rounded-2xl
                shadow-2xl lg:border lg:border-white/50
                w-full lg:max-w-lg
                max-h-[90vh] overflow-y-auto"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-y-full lg:translate-y-0 lg:opacity-0 lg:scale-95"
         x-transition:enter-end="translate-y-0 lg:opacity-100 lg:scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-y-0 lg:opacity-100 lg:scale-100"
         x-transition:leave-end="translate-y-full lg:translate-y-0 lg:opacity-0 lg:scale-95"
         @click.stop>

        {{-- Drag Handle (mobile only) --}}
        <div class="flex justify-center py-3 lg:hidden">
            <div class="w-10 h-1 bg-gray-300 rounded-full"></div>
        </div>

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 pb-4 lg:p-6 lg:border-b lg:border-gray-100/60">
            <div>
                <h3 class="text-lg lg:text-base font-bold text-slate-800 lg:text-[#4682B4]">
                    <span class="lg:hidden">New Laundry Request</span>
                    <span class="hidden lg:inline">Submit Laundry Request</span>
                </h3>
                <p class="text-xs text-slate-400 mt-0.5">
                    <span class="lg:hidden">We'll take care of it</span>
                    <span class="hidden lg:inline text-[10px]">Fill in the details and our staff will process your order.</span>
                </p>
            </div>
            <button @click="showRequestModal = false"
                    class="w-10 h-10 rounded-full bg-gray-100 lg:bg-transparent flex items-center justify-center text-gray-500 hover:text-gray-600 lg:rounded-xl lg:hover:bg-gray-100/60 active:bg-gray-200 transition-colors"
                    aria-label="Close">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-linecap="round"/></svg>
            </button>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('customer.orders.request') }}" class="px-6 pb-8 lg:p-6 space-y-5">
            @csrf

            {{-- Estimated Weight --}}
            <div>
                <label for="estimated_weight" class="block text-sm font-semibold text-slate-700 lg:text-xs lg:font-bold lg:text-gray-600 mb-2 lg:mb-1.5">Estimated Weight</label>
                <div class="relative">
                    <input type="number" name="estimated_weight" id="estimated_weight"
                           step="0.1" min="0.5" max="100" required
                           x-model="form.weight"
                           class="w-full px-4 py-3.5 lg:py-2.5 text-base lg:text-sm bg-slate-50 lg:bg-white/80 border border-slate-200 lg:border-gray-200/60 rounded-2xl lg:rounded-xl focus:ring-2 focus:ring-[#87CEEB]/40 focus:border-[#87CEEB] outline-none transition pr-12"
                           placeholder="e.g. 3.5"
                           inputmode="decimal">
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-sm text-slate-400 font-medium">kg</span>
                </div>
            </div>

            {{-- Services --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 lg:text-xs lg:font-bold lg:text-gray-600 mb-2">Services</label>
                <div class="grid grid-cols-3 gap-3 lg:gap-2.5">
                    @foreach(['wash' => ['Wash', 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'], 'dry' => ['Dry', 'M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z'], 'fold' => ['Fold', 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4']] as $key => [$label, $icon])
                    <label class="relative cursor-pointer">
                        <input type="checkbox" name="services[]" value="{{ $key }}"
                               x-model="form.services" class="peer sr-only">
                        <div class="flex flex-col items-center gap-1.5 py-4 lg:py-3 rounded-2xl border-2 lg:border border-slate-100 lg:border-gray-200/60 bg-white lg:bg-white/60 text-slate-400 lg:text-gray-500
                                    peer-checked:bg-[#87CEEB]/15 peer-checked:border-[#87CEEB]/50 peer-checked:text-[#4682B4] peer-checked:shadow-lg lg:peer-checked:shadow-none
                                    transition-all duration-200 active:scale-95 min-h-12">
                            <svg class="w-6 h-6 lg:w-5 lg:h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $icon }}"/></svg>
                            <span class="text-xs font-bold">{{ $label }}</span>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Estimated Price --}}
            <div x-show="form.services.length > 0 && form.weight > 0" x-transition
                 class="bg-gradient-to-r from-[#87CEEB]/10 to-sky-100/30 rounded-2xl lg:rounded-xl px-4 py-3.5 lg:py-3 border border-[#87CEEB]/20">
                <div class="flex items-center justify-between">
                    <span class="text-sm lg:text-xs text-slate-500 font-medium lg:font-semibold">Estimated Price</span>
                    <span class="text-xl lg:text-base font-black text-[#4682B4]" x-text="'₱' + estimatedPrice.toFixed(2)"></span>
                </div>
            </div>

            {{-- Payment Method --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 lg:text-xs lg:font-bold lg:text-gray-600 mb-2">Payment</label>
                <div class="grid grid-cols-3 gap-3 lg:gap-2.5">
                    @foreach(['cash' => 'Cash', 'gcash' => 'GCash', 'maya' => 'Maya'] as $key => $label)
                    <label class="relative cursor-pointer">
                        <input type="radio" name="payment_method" value="{{ $key }}"
                               x-model="form.paymentMethod" class="peer sr-only" {{ $loop->first ? 'checked' : '' }}>
                        <div class="flex items-center justify-center px-3 py-3 lg:py-2.5 text-sm lg:text-xs font-semibold lg:font-semibold rounded-2xl border-2 lg:border border-slate-100 lg:border-gray-200/60 bg-white lg:bg-white/60 text-slate-500 lg:text-gray-500
                                    peer-checked:bg-[#87CEEB]/15 peer-checked:border-[#87CEEB]/50 peer-checked:text-[#4682B4]
                                    transition-all duration-200 active:scale-95 min-h-12">
                            {{ $label }}
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Reference Number --}}
            <div x-show="form.paymentMethod === 'gcash' || form.paymentMethod === 'maya'" x-transition>
                <label for="payment_reference" class="block text-sm font-semibold text-slate-700 lg:text-xs lg:font-bold lg:text-gray-600 mb-2 lg:mb-1.5">Reference Number</label>
                <input type="text" name="payment_reference" id="payment_reference"
                       x-model="form.reference"
                       class="w-full px-4 py-3.5 lg:py-2.5 text-base lg:text-sm bg-slate-50 lg:bg-white/80 border border-slate-200 lg:border-gray-200/60 rounded-2xl lg:rounded-xl focus:ring-2 focus:ring-[#87CEEB]/40 focus:border-[#87CEEB] outline-none transition"
                       placeholder="Enter payment reference"
                       inputmode="numeric">
                @if($settings->gcash_number)
                    <p class="text-xs text-slate-400 mt-2 lg:mt-1.5">Send to: <span class="font-bold text-[#4682B4]">{{ $settings->gcash_number }}</span></p>
                @endif
                @if($settings->payment_instructions)
                    <p class="text-xs text-slate-400 mt-1 leading-relaxed">{{ $settings->payment_instructions }}</p>
                @endif
            </div>

            {{-- Special Instructions --}}
            <div>
                <label for="special_instructions" class="block text-sm font-semibold text-slate-700 lg:text-xs lg:font-bold lg:text-gray-600 mb-2 lg:mb-1.5">
                    Notes <span class="text-slate-400 lg:text-gray-400 font-normal text-xs">(optional)</span>
                </label>
                <textarea name="special_instructions" id="special_instructions" rows="2"
                          x-model="form.instructions"
                          class="w-full px-4 py-3.5 lg:py-2.5 text-base lg:text-sm bg-slate-50 lg:bg-white/80 border border-slate-200 lg:border-gray-200/60 rounded-2xl lg:rounded-xl focus:ring-2 focus:ring-[#87CEEB]/40 focus:border-[#87CEEB] outline-none transition resize-none"
                          placeholder="e.g. Separate whites and colors"></textarea>
            </div>

            {{-- Actions --}}
            <div class="space-y-2 lg:space-y-0 lg:flex lg:items-center lg:justify-end lg:gap-3 pt-2">
                <button type="submit"
                        class="w-full lg:w-auto py-4 lg:py-2.5 lg:px-7 bg-gradient-to-r from-[#87CEEB] to-sky-500 lg:to-sky-600 text-white font-bold text-base lg:text-xs rounded-2xl shadow-lg shadow-sky-200/50 lg:shadow-sky-200/40 active:scale-[0.98] lg:hover:shadow-xl lg:hover:-translate-y-0.5 transition-all duration-200 min-h-12 lg:min-h-0 lg:inline-flex lg:items-center lg:gap-2 lg:order-2">
                    <svg class="w-4 h-4 hidden lg:inline-block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    Submit Request
                </button>
                <button type="button" @click="showRequestModal = false"
                        class="w-full lg:w-auto py-3 lg:py-2.5 lg:px-5 text-sm lg:text-xs font-semibold text-slate-400 lg:text-gray-500 active:text-slate-600 lg:hover:text-gray-700 rounded-2xl lg:hover:bg-gray-100/60 transition-colors min-h-12 lg:min-h-0 lg:order-1">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>


{{-- ═══════════════════════════════════════════════════════════════════
     Alpine.js Component
═══════════════════════════════════════════════════════════════════ --}}
<script>
function customerDashboard() {
    return {
        showMobileMenu: false,
        showRequestModal: false,
        form: {
            weight: '',
            services: [],
            paymentMethod: 'cash',
            reference: '',
            instructions: '',
        },
        prices: @js([
            'wash' => (float) $settings->wash_price,
            'dry' => (float) $settings->dry_price,
            'fold' => (float) $settings->fold_price,
        ]),
        get estimatedPrice() {
            if (!this.form.weight || this.form.services.length === 0) return 0;
            let total = 0;
            const w = parseFloat(this.form.weight) || 0;
            this.form.services.forEach(svc => { total += (this.prices[svc] || 0) * w; });
            return total;
        },
        scrollToOrders() {
            const target = window.innerWidth >= 1024
                ? this.$refs.desktopOrdersSection
                : this.$refs.mobileOrdersSection;

            if (!target) {
                return;
            }

            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    };
}
</script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
