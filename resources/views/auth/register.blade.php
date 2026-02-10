@extends('layouts.guest')
@section('content')
<div class="bg-white rounded-2xl shadow-lg p-8">
    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">🧺 Create Account</h1>
        <p class="mt-1 text-sm text-gray-500">Register with your name and phone number</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                placeholder="Juan Dela Cruz">
        </div>

        <div>
            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
            <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" required
                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                placeholder="09171234567">
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input id="password" type="password" name="password" required
                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                placeholder="••••••••">
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                placeholder="••••••••">
        </div>

        <button type="submit"
            class="w-full rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 transition">
            Register
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-500">
        Already have an account?
        <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">Sign in</a>
    </p>
</div>
@endsection
