@extends('layouts.admin')

@section('title', 'Add User – GeloWash')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Add User</h1>
            <p class="text-gray-400 text-sm mt-1">Create a new staff or customer account</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500 hover:text-sky-600 transition flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back
        </a>
    </div>

    <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
        @csrf

        <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl p-6 space-y-4">
            <div>
                <label for="name" class="text-sm font-medium text-gray-600 mb-1 block">Full Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-sky-300 focus:border-sky-400 outline-none transition bg-white/50 text-sm"
                       placeholder="Juan Dela Cruz">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="text-sm font-medium text-gray-600 mb-1 block">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-sky-300 focus:border-sky-400 outline-none transition bg-white/50 text-sm"
                       placeholder="user@example.com">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="phone" class="text-sm font-medium text-gray-600 mb-1 block">Phone</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-sky-300 focus:border-sky-400 outline-none transition bg-white/50 text-sm"
                       placeholder="09XX-XXX-XXXX">
                @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="role" class="text-sm font-medium text-gray-600 mb-1 block">Role</label>
                <select id="role" name="role" required
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-sky-300 focus:border-sky-400 outline-none transition bg-white/50 text-sm">
                    <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>Staff</option>
                    <option value="customer" {{ old('role') === 'customer' ? 'selected' : '' }}>Customer</option>
                </select>
                @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password" class="text-sm font-medium text-gray-600 mb-1 block">Password</label>
                <input type="password" id="password" name="password" required
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-sky-300 focus:border-sky-400 outline-none transition bg-white/50 text-sm"
                       placeholder="Min 8 characters">
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="text-sm font-medium text-gray-600 mb-1 block">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-sky-300 focus:border-sky-400 outline-none transition bg-white/50 text-sm"
                       placeholder="Repeat password">
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit"
                    class="flex-1 bg-gradient-to-r from-sky-500 to-sky-600 text-white py-3 rounded-xl font-semibold hover:from-sky-600 hover:to-sky-700 transition shadow-lg shadow-sky-200 min-h-[44px] flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                Create User
            </button>
            <a href="{{ route('admin.users.index') }}" class="px-6 py-3 rounded-xl font-semibold border border-gray-200 text-gray-600 hover:bg-gray-50 transition min-h-[44px] flex items-center">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
