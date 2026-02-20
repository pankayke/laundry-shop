{{--
    GeloWash — Admin & Staff Shared Layout
    Fixed left sidebar (w-64) with full navigation, mobile hamburger overlay
    All admin + staff pages extend this layout.
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#87CEEB">
    <title>@yield('title', 'Admin – GeloWash')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50/80 to-indigo-50 font-sans min-h-screen antialiased"
      x-data="{ sidebarOpen: false }" x-cloak>

{{-- ── Fixed Left Sidebar (desktop) ──────────────────────────────── --}}
<aside class="fixed inset-y-0 left-0 z-40 w-64 bg-white/60 backdrop-blur-2xl border-r border-white/50 shadow-2xl
              transform transition-transform duration-300 ease-in-out
              -translate-x-full lg:translate-x-0"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
       @click.away="sidebarOpen = false"
       aria-label="Admin navigation">

    {{-- Brand --}}
    <div class="h-16 px-6 flex items-center gap-3 border-b border-white/30 shrink-0">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
            <div class="w-10 h-10 bg-gradient-to-br from-[#87CEEB] to-[#FFD700] rounded-2xl flex items-center justify-center shadow-lg shadow-sky-200/40 transition-transform group-hover:scale-105">
                <svg viewBox="0 0 24 24" class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="14" r="7"/><circle cx="12" cy="14" r="3"/>
                    <path d="M7 4h10a2 2 0 012 2v2H5V6a2 2 0 012-2z"/>
                    <circle cx="9" cy="6" r="0.8" fill="currentColor"/><circle cx="12" cy="6" r="0.8" fill="currentColor"/>
                </svg>
            </div>
            <div>
                <h1 class="text-lg font-black tracking-tight leading-tight">
                    <span class="bg-gradient-to-r from-[#87CEEB] to-[#4682B4] bg-clip-text text-transparent italic">Gelo</span><span class="bg-gradient-to-r from-[#4682B4] to-[#FFD700] bg-clip-text text-transparent">Wash</span>
                </h1>
                <p class="text-[9px] text-[#4682B4]/60 font-semibold tracking-widest uppercase -mt-0.5">Admin Panel</p>
            </div>
        </a>
        {{-- Close button (mobile only) --}}
        <button @click="sidebarOpen = false" class="lg:hidden ml-auto p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100/60 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-linecap="round"/></svg>
        </button>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-5 space-y-1 overflow-y-auto">

        {{-- MAIN --}}
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-3 mb-2">Main</p>

        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                  {{ request()->routeIs('admin.dashboard') ? 'bg-sky-100/80 text-[#4682B4] font-semibold shadow-sm' : 'text-gray-500 hover:bg-white/60 hover:text-gray-700' }}">
            <div class="w-8 h-8 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-sky-200/60' : 'bg-gray-100/60' }} flex items-center justify-center transition-colors">
                <svg class="w-[18px] h-[18px] {{ request()->routeIs('admin.dashboard') ? 'text-[#4682B4]' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1v-2zM14 12a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1h-4a1 1 0 01-1-1v-5z"/>
                </svg>
            </div>
            Dashboard
        </a>

        <a href="{{ route('staff.dashboard') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                  {{ request()->routeIs('staff.dashboard') ? 'bg-sky-100/80 text-[#4682B4] font-semibold shadow-sm' : 'text-gray-500 hover:bg-white/60 hover:text-gray-700' }}">
            <div class="w-8 h-8 rounded-lg {{ request()->routeIs('staff.dashboard') ? 'bg-sky-200/60' : 'bg-gray-100/60' }} flex items-center justify-center transition-colors">
                <svg class="w-[18px] h-[18px] {{ request()->routeIs('staff.dashboard') ? 'text-[#4682B4]' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
            </div>
            Staff Board
        </a>

        {{-- ORDERS --}}
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-3 mt-5 mb-2">Orders</p>

        <a href="{{ route('staff.orders.create') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                  {{ request()->routeIs('staff.orders.create') ? 'bg-sky-100/80 text-[#4682B4] font-semibold shadow-sm' : 'text-gray-500 hover:bg-white/60 hover:text-gray-700' }}">
            <div class="w-8 h-8 rounded-lg {{ request()->routeIs('staff.orders.create') ? 'bg-sky-200/60' : 'bg-gray-100/60' }} flex items-center justify-center transition-colors">
                <svg class="w-[18px] h-[18px] {{ request()->routeIs('staff.orders.create') ? 'text-[#4682B4]' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            New Order
        </a>

        <a href="{{ route('staff.orders.search') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                  {{ request()->routeIs('staff.orders.search') ? 'bg-sky-100/80 text-[#4682B4] font-semibold shadow-sm' : 'text-gray-500 hover:bg-white/60 hover:text-gray-700' }}">
            <div class="w-8 h-8 rounded-lg {{ request()->routeIs('staff.orders.search') ? 'bg-sky-200/60' : 'bg-gray-100/60' }} flex items-center justify-center transition-colors">
                <svg class="w-[18px] h-[18px] {{ request()->routeIs('staff.orders.search') ? 'text-[#4682B4]' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            Search Orders
        </a>

        <a href="{{ route('track.order') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                  {{ request()->routeIs('track.order') ? 'bg-sky-100/80 text-[#4682B4] font-semibold shadow-sm' : 'text-gray-500 hover:bg-white/60 hover:text-gray-700' }}">
            <div class="w-8 h-8 rounded-lg {{ request()->routeIs('track.order') ? 'bg-sky-200/60' : 'bg-gray-100/60' }} flex items-center justify-center transition-colors">
                <svg class="w-[18px] h-[18px] {{ request()->routeIs('track.order') ? 'text-[#4682B4]' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5"/>
                </svg>
            </div>
            Track Order
        </a>

        {{-- MANAGEMENT --}}
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-3 mt-5 mb-2">Management</p>

        <a href="{{ route('admin.users.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                  {{ request()->routeIs('admin.users.*') ? 'bg-sky-100/80 text-[#4682B4] font-semibold shadow-sm' : 'text-gray-500 hover:bg-white/60 hover:text-gray-700' }}">
            <div class="w-8 h-8 rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-sky-200/60' : 'bg-gray-100/60' }} flex items-center justify-center transition-colors">
                <svg class="w-[18px] h-[18px] {{ request()->routeIs('admin.users.*') ? 'text-[#4682B4]' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                </svg>
            </div>
            Users
        </a>

        <a href="{{ route('admin.sales') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                  {{ request()->routeIs('admin.sales*') ? 'bg-sky-100/80 text-[#4682B4] font-semibold shadow-sm' : 'text-gray-500 hover:bg-white/60 hover:text-gray-700' }}">
            <div class="w-8 h-8 rounded-lg {{ request()->routeIs('admin.sales*') ? 'bg-sky-200/60' : 'bg-gray-100/60' }} flex items-center justify-center transition-colors">
                <svg class="w-[18px] h-[18px] {{ request()->routeIs('admin.sales*') ? 'text-[#4682B4]' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            Sales Reports
        </a>

        <a href="{{ route('admin.settings') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                  {{ request()->routeIs('admin.settings*') ? 'bg-sky-100/80 text-[#4682B4] font-semibold shadow-sm' : 'text-gray-500 hover:bg-white/60 hover:text-gray-700' }}">
            <div class="w-8 h-8 rounded-lg {{ request()->routeIs('admin.settings*') ? 'bg-sky-200/60' : 'bg-gray-100/60' }} flex items-center justify-center transition-colors">
                <svg class="w-[18px] h-[18px] {{ request()->routeIs('admin.settings*') ? 'text-[#4682B4]' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            Settings
        </a>
    </nav>

    {{-- User Card --}}
    <div class="px-3 py-4 border-t border-white/30 shrink-0">
        <div class="flex items-center gap-3 px-3 py-3 rounded-xl bg-white/40 backdrop-blur-sm">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#87CEEB] to-sky-600 flex items-center justify-center shadow-md shadow-sky-200/30 shrink-0">
                <span class="text-sm font-black text-white">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-bold text-gray-800 truncate">{{ Auth::user()->name }}</p>
                <p class="text-[10px] text-gray-400 truncate">{{ ucfirst(Auth::user()->role) }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="p-2 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50/60 transition-all duration-200" title="Sign out">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </form>
        </div>
    </div>
</aside>

{{-- ── Mobile sidebar backdrop ────────────────────────────────────── --}}
<div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
     @click="sidebarOpen = false"
     class="fixed inset-0 z-30 bg-black/30 backdrop-blur-sm lg:hidden"></div>

{{-- ── Top Bar (mobile only) ─────────────────────────────────────── --}}
<header class="fixed top-0 left-0 right-0 h-16 bg-white/70 backdrop-blur-xl border-b border-white/50 shadow-sm z-20 lg:hidden">
    <div class="h-full px-4 flex items-center justify-between">
        <button @click="sidebarOpen = true" class="p-2 -ml-1 rounded-xl text-gray-500 hover:bg-gray-100/60 transition" aria-label="Open menu">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round"/></svg>
        </button>
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
            <div class="w-8 h-8 bg-gradient-to-br from-[#87CEEB] to-[#FFD700] rounded-xl flex items-center justify-center shadow-md">
                <svg viewBox="0 0 24 24" class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="14" r="7"/><circle cx="12" cy="14" r="3"/><path d="M7 4h10a2 2 0 012 2v2H5V6a2 2 0 012-2z"/></svg>
            </div>
            <span class="text-base font-black">
                <span class="bg-gradient-to-r from-[#87CEEB] to-[#4682B4] bg-clip-text text-transparent italic">Gelo</span><span class="bg-gradient-to-r from-[#4682B4] to-[#FFD700] bg-clip-text text-transparent">Wash</span>
            </span>
        </a>
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#87CEEB] to-sky-600 flex items-center justify-center shadow-md text-white font-bold text-sm">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
    </div>
</header>

{{-- ── Main Content Area ─────────────────────────────────────────── --}}
<main class="lg:ml-64 min-h-screen pt-20 lg:pt-6 pb-10 px-4 sm:px-6 lg:px-8">
    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="max-w-7xl mx-auto mb-4">
            <div class="bg-green-50/80 backdrop-blur border border-green-200/60 text-green-700 px-4 py-3 rounded-2xl flex items-center gap-3 shadow-sm" role="alert">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto mb-4">
            <div class="bg-red-50/80 backdrop-blur border border-red-200/60 text-red-700 px-4 py-3 rounded-2xl flex items-center gap-3 shadow-sm" role="alert">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="max-w-7xl mx-auto mb-4">
            <div class="bg-red-50/80 backdrop-blur border border-red-200/60 text-red-700 px-4 py-3 rounded-2xl shadow-sm">
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

{{-- Alpine.js CDN --}}
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@stack('scripts')
</body>
</html>
