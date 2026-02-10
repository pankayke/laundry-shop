@php $user = auth()->user(); @endphp

<nav class="bg-white border-b border-gray-200 shadow-sm" x-data="{ open: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            {{-- Logo / Brand --}}
            <div class="flex items-center gap-6">
                <a href="/" class="text-xl font-bold text-indigo-600 tracking-tight">
                    🧺 Laundry Shop
                </a>

                @auth
                    <div class="hidden md:flex items-center gap-4 text-sm font-medium text-gray-600">
                        @if ($user->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 {{ request()->routeIs('admin.dashboard') ? 'text-indigo-600' : '' }}">Dashboard</a>
                            <a href="{{ route('admin.sales') }}" class="hover:text-indigo-600 {{ request()->routeIs('admin.sales*') ? 'text-indigo-600' : '' }}">Sales</a>
                            <a href="{{ route('admin.users.index') }}" class="hover:text-indigo-600 {{ request()->routeIs('admin.users*') ? 'text-indigo-600' : '' }}">Users</a>
                            <a href="{{ route('admin.settings') }}" class="hover:text-indigo-600 {{ request()->routeIs('admin.settings*') ? 'text-indigo-600' : '' }}">Settings</a>
                            <span class="text-gray-300">|</span>
                            <a href="{{ route('staff.dashboard') }}" class="hover:text-indigo-600 {{ request()->routeIs('staff.*') ? 'text-indigo-600' : '' }}">Staff Panel</a>
                        @elseif ($user->isStaff())
                            <a href="{{ route('staff.dashboard') }}" class="hover:text-indigo-600 {{ request()->routeIs('staff.dashboard') ? 'text-indigo-600' : '' }}">Dashboard</a>
                            <a href="{{ route('staff.orders.create') }}" class="hover:text-indigo-600 {{ request()->routeIs('staff.orders.create') ? 'text-indigo-600' : '' }}">New Order</a>
                            <a href="{{ route('staff.orders.search') }}" class="hover:text-indigo-600 {{ request()->routeIs('staff.orders.search') ? 'text-indigo-600' : '' }}">Search</a>
                        @else
                            <a href="{{ route('customer.dashboard') }}" class="hover:text-indigo-600 {{ request()->routeIs('customer.dashboard') ? 'text-indigo-600' : '' }}">My Orders</a>
                            <a href="{{ route('track.order') }}" class="hover:text-indigo-600 {{ request()->routeIs('track.order') ? 'text-indigo-600' : '' }}">Track</a>
                        @endif
                    </div>
                @endauth
            </div>

            {{-- Right side --}}
            <div class="flex items-center gap-4">
                @auth
                    <span class="hidden sm:inline text-sm text-gray-500">
                        {{ $user->name }}
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                            {{ $user->isAdmin() ? 'bg-purple-100 text-purple-700' : ($user->isStaff() ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700') }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-gray-500 hover:text-red-600 font-medium">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600">Login</a>
                    <a href="{{ route('register') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition">Register</a>
                @endauth

                {{-- Mobile hamburger --}}
                @auth
                <button @click="open = !open" class="md:hidden inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                @endauth
            </div>
        </div>
    </div>

    {{-- Mobile menu --}}
    @auth
    <div x-show="open" x-transition class="md:hidden border-t border-gray-200 bg-white px-4 pb-4 pt-2 space-y-1">
        @if ($user->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="block rounded-md px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">Admin Dashboard</a>
            <a href="{{ route('admin.sales') }}" class="block rounded-md px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">Sales</a>
            <a href="{{ route('admin.users.index') }}" class="block rounded-md px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">Users</a>
            <a href="{{ route('admin.settings') }}" class="block rounded-md px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">Settings</a>
            <a href="{{ route('staff.dashboard') }}" class="block rounded-md px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">Staff Panel</a>
        @elseif ($user->isStaff())
            <a href="{{ route('staff.dashboard') }}" class="block rounded-md px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">Dashboard</a>
            <a href="{{ route('staff.orders.create') }}" class="block rounded-md px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">New Order</a>
            <a href="{{ route('staff.orders.search') }}" class="block rounded-md px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">Search Orders</a>
        @else
            <a href="{{ route('customer.dashboard') }}" class="block rounded-md px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">My Orders</a>
            <a href="{{ route('track.order') }}" class="block rounded-md px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">Track Order</a>
        @endif
    </div>
    @endauth
</nav>
