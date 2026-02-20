{{-- GeloWash — Shared Navigation Bar (used by layouts.app) --}}
<nav class="fixed top-0 inset-x-0 z-50 h-16 bg-white/80 backdrop-blur-xl border-b border-white/50 shadow-sm">
    <div class="max-w-7xl mx-auto h-full px-4 sm:px-6 lg:px-8 flex items-center justify-between">

        {{-- Brand --}}
        <a href="/" class="flex items-center gap-2.5 group shrink-0">
            <div class="w-9 h-9 bg-gradient-to-br from-[#87CEEB] to-[#FFD700] rounded-xl flex items-center justify-center shadow-md shadow-sky-200/30 transition-transform group-hover:scale-105">
                <svg viewBox="0 0 24 24" class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="14" r="7"/>
                    <circle cx="12" cy="14" r="3"/>
                    <path d="M7 4h10a2 2 0 012 2v2H5V6a2 2 0 012-2z"/>
                    <circle cx="9" cy="6" r="0.8" fill="currentColor"/>
                    <circle cx="12" cy="6" r="0.8" fill="currentColor"/>
                </svg>
            </div>
            <div>
                <span class="text-lg font-black tracking-tight">
                    <span class="bg-gradient-to-r from-[#87CEEB] to-[#4682B4] bg-clip-text text-transparent italic">Gelo</span><span class="bg-gradient-to-r from-[#4682B4] to-[#FFD700] bg-clip-text text-transparent">Wash</span>
                </span>
            </div>
        </a>

        {{-- Desktop Links --}}
        <div class="hidden sm:flex items-center gap-1">
            <a href="{{ route('track.order') }}"
               class="px-4 py-2 text-sm font-medium rounded-xl transition-all duration-200
                      {{ request()->routeIs('track.order') ? 'text-[#4682B4] bg-[#87CEEB]/15 font-semibold' : 'text-gray-500 hover:text-[#4682B4] hover:bg-[#87CEEB]/10' }}">
                Track Order
            </a>

            @auth
                @if(Auth::user()->hasRole('customer'))
                    <a href="{{ route('customer.dashboard') }}"
                       class="px-4 py-2 text-sm font-medium rounded-xl transition-all duration-200
                              {{ request()->routeIs('customer.dashboard') ? 'text-[#4682B4] bg-[#87CEEB]/15 font-semibold' : 'text-gray-500 hover:text-[#4682B4] hover:bg-[#87CEEB]/10' }}">
                        Dashboard
                    </a>
                @endif

                @if(Auth::user()->hasRole('staff') || Auth::user()->hasRole('admin'))
                    <a href="{{ Auth::user()->hasRole('admin') ? '/admin' : '/staff' }}"
                       class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-[#4682B4] hover:bg-[#87CEEB]/10 rounded-xl transition-all duration-200">
                        Panel
                    </a>
                @endif

                <div class="w-px h-5 bg-gray-200 mx-1"></div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-gray-400 hover:text-red-500 rounded-xl hover:bg-red-50/60 transition-all duration-200">
                        Sign Out
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}"
                   class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-[#4682B4] hover:bg-[#87CEEB]/10 rounded-xl transition-all duration-200">
                    Sign In
                </a>
                @if(Route::has('register'))
                    <a href="{{ route('register') }}"
                       class="px-5 py-2 text-sm font-bold text-white bg-gradient-to-r from-[#87CEEB] to-sky-600 rounded-xl shadow-md shadow-sky-200/30 hover:shadow-lg transition-all duration-200">
                        Register
                    </a>
                @endif
            @endauth
        </div>

        {{-- Mobile: minimal right-side items --}}
        <div class="sm:hidden flex items-center gap-2">
            @auth
                <a href="{{ route('customer.dashboard') }}" class="p-2 text-[#4682B4] rounded-xl hover:bg-[#87CEEB]/10 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1"/></svg>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="p-2 text-gray-400 hover:text-red-500 rounded-xl hover:bg-red-50/60 transition" title="Sign out">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-[#4682B4] rounded-xl hover:bg-[#87CEEB]/10 transition">
                    Sign In
                </a>
            @endauth
        </div>
    </div>
</nav>
