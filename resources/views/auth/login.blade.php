@extends('layouts.guest')
@section('content')
<div class="bg-white rounded-2xl shadow-lg p-8">
    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">🧺 Welcome Back</h1>
        <p class="mt-1 text-sm text-gray-500">Sign in with your phone number or email</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="login" class="block text-sm font-medium text-gray-700 mb-1">Phone or Email</label>
            <input id="login" type="text" name="login" value="{{ old('login') }}" required autofocus
                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                placeholder="09171234567 or email@example.com">
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input id="password" type="password" name="password" required
                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                placeholder="••••••••">
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 text-sm text-gray-600">
                <input type="checkbox" name="remember" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                Remember me
            </label>
        </div>

        <button type="submit"
            class="w-full rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 transition">
            Sign In
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-500">
        Don't have an account?
        <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500">Register</a>
    </p>
</div>
@endsection
