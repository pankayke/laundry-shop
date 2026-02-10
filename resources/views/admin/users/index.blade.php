@extends('layouts.app')
@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-3">
    <h1 class="text-2xl font-bold text-gray-900">Manage Users</h1>
    <a href="{{ route('admin.users.create') }}"
        class="inline-flex items-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 transition">
        + New User
    </a>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col sm:flex-row gap-3 mb-6">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, phone, or email"
        class="flex-1 rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
    <select name="role" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
        <option value="">All Roles</option>
        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
        <option value="staff" {{ request('role') === 'staff' ? 'selected' : '' }}>Staff</option>
        <option value="customer" {{ request('role') === 'customer' ? 'selected' : '' }}>Customer</option>
    </select>
    <button type="submit"
        class="rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 transition">
        Filter
    </button>
</form>

{{-- Users Table --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Phone</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Role</th>
                    <th class="px-4 py-3">Joined</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $user->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $user->phone ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $user->email ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                                {{ $user->isAdmin() ? 'bg-purple-100 text-purple-700' : ($user->isStaff() ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700') }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-500">{{ $user->created_at->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.users.edit', $user) }}"
                                class="text-indigo-600 hover:text-indigo-800 text-xs font-medium">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">{{ $users->links() }}</div>
@endsection
