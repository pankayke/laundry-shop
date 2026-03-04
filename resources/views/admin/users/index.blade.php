@extends('layouts.admin')

@section('title', 'Users – GeloWash')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Users</h1>
            <p class="text-gray-400 text-sm mt-1">Manage staff and customer accounts</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-sky-500 to-sky-600 text-white px-5 py-2.5 rounded-xl font-semibold hover:from-sky-600 hover:to-sky-700 transition shadow-lg shadow-sky-200 min-h-[44px]">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            Add User
        </a>
    </div>

    {{-- Search --}}
    <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl p-6">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex gap-3">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, or phone"
                       class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-sky-300 focus:border-sky-400 outline-none transition bg-white/50 text-sm">
            </div>
            <button type="submit" class="bg-gradient-to-r from-sky-500 to-sky-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-sky-600 hover:to-sky-700 transition shadow-lg shadow-sky-200 min-h-[44px]">
                Search
            </button>
        </form>
    </div>

    {{-- Users Table --}}
    <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100">
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Name</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Email</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Phone</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Role</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Joined</th>
                        <th class="px-4 py-3 text-center font-semibold text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-sky-50/50 transition">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-sky-400 to-sky-500 flex items-center justify-center text-white font-bold text-sm">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span class="font-semibold text-gray-700">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ $user->email }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $user->phone ?? '—' }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $roleColors = [
                                        'admin'    => 'bg-red-100 text-red-700',
                                        'staff'    => 'bg-sky-100 text-sky-700',
                                        'customer' => 'bg-green-100 text-green-700',
                                    ];
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $roleColors[$user->role] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center gap-1 text-sky-600 hover:text-sky-700 font-medium text-sm p-2 -m-2 rounded-lg hover:bg-sky-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        Edit
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('Delete {{ $user->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 p-2 -m-2 rounded-lg hover:bg-red-50 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-400">No users found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
