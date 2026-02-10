@extends('layouts.app')
@section('content')
<div class="max-w-xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit User – {{ $user->name }}</h1>

    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-5">
        @csrf @method('PUT')

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
            <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required
                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required
                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        <div>
            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
            <input id="phone" type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        <div>
            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
            <select id="role" name="role" required
                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="staff" {{ old('role', $user->role) === 'staff' ? 'selected' : '' }}>Staff</option>
                <option value="customer" {{ old('role', $user->role) === 'customer' ? 'selected' : '' }}>Customer</option>
            </select>
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password <span class="text-gray-400">(leave blank to keep current)</span></label>
            <input id="password" type="password" name="password"
                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit"
                class="rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 transition">
                Update User
            </button>
            <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
        </div>
    </form>
</div>
@endsection
