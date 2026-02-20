@extends('layouts.admin')

@section('title', 'Settings – GeloWash')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    {{-- Toast --}}
    @if(session('success'))
        @include('components.glass-toast', ['message' => session('success'), 'type' => 'success'])
    @endif

    {{-- Header --}}
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Shop Settings</h1>
        <p class="text-gray-400 text-sm mt-1">Manage shop information and service pricing</p>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Shop Info --}}
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl p-6">
            <h2 class="text-lg font-bold text-gray-700 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-sky-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                Shop Information
            </h2>
            <div class="space-y-4">
                <div>
                    <label for="shop_name" class="text-sm font-medium text-gray-600 mb-1 block">Shop Name</label>
                    <input type="text" id="shop_name" name="shop_name" value="{{ old('shop_name', $settings->shop_name) }}" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-sky-300 focus:border-sky-400 outline-none transition bg-white/50 text-sm">
                </div>
                <div>
                    <label for="shop_address" class="text-sm font-medium text-gray-600 mb-1 block">Address</label>
                    <input type="text" id="shop_address" name="shop_address" value="{{ old('shop_address', $settings->shop_address) }}" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-sky-300 focus:border-sky-400 outline-none transition bg-white/50 text-sm">
                </div>
                <div>
                    <label for="shop_phone" class="text-sm font-medium text-gray-600 mb-1 block">Phone</label>
                    <input type="text" id="shop_phone" name="shop_phone" value="{{ old('shop_phone', $settings->shop_phone) }}" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-sky-300 focus:border-sky-400 outline-none transition bg-white/50 text-sm">
                </div>
            </div>
        </div>

        {{-- Pricing --}}
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl p-6">
            <h2 class="text-lg font-bold text-gray-700 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-sky-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Service Pricing (per kg)
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-sky-50 rounded-xl p-4">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-8 h-8 text-sky-600" viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="16" cy="16" r="13"/>
                            <circle cx="16" cy="17" r="6"/>
                            <circle cx="16" cy="17" r="2.5" fill="#87CEEB"/>
                            <rect x="10" y="6" width="12" height="5" rx="2.5"/>
                        </svg>
                        <span class="font-bold text-sky-700">Wash</span>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 font-semibold">₱</span>
                        <input type="number" name="wash_price" value="{{ old('wash_price', $settings->wash_price) }}" step="0.01" min="0" required
                               class="w-full pl-8 pr-4 py-3 border border-sky-200 rounded-xl focus:ring-2 focus:ring-sky-300 focus:border-sky-400 outline-none transition text-sm bg-white">
                    </div>
                </div>

                <div class="bg-amber-50 rounded-xl p-4">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 32 32">
                            <circle cx="16" cy="16" r="4"/>
                            <path d="M16 4v2m0 20v2m12-12h-2M4 16H2m20.485 8.485l-1.414-1.414M8.929 8.929L7.515 7.515m16.97 0l-1.414 1.414M8.929 23.071l-1.414 1.414"/>
                        </svg>
                        <span class="font-bold text-amber-700">Dry</span>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 font-semibold">₱</span>
                        <input type="number" name="dry_price" value="{{ old('dry_price', $settings->dry_price) }}" step="0.01" min="0" required
                               class="w-full pl-8 pr-4 py-3 border border-amber-200 rounded-xl focus:ring-2 focus:ring-amber-300 focus:border-amber-400 outline-none transition text-sm bg-white">
                    </div>
                </div>

                <div class="bg-purple-50 rounded-xl p-4">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 32 32">
                            <path d="M8 10h16M8 10a3 3 0 110-6h16a3 3 0 110 6M8 10v14a3 3 0 003 3h10a3 3 0 003-3V10m-11 6h6"/>
                        </svg>
                        <span class="font-bold text-purple-700">Fold</span>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 font-semibold">₱</span>
                        <input type="number" name="fold_price" value="{{ old('fold_price', $settings->fold_price) }}" step="0.01" min="0" required
                               class="w-full pl-8 pr-4 py-3 border border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-300 focus:border-purple-400 outline-none transition text-sm bg-white">
                    </div>
                </div>
            </div>
        </div>

        {{-- Payment Settings --}}
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl p-6">
            <h2 class="text-lg font-bold text-gray-700 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/></svg>
                Online Payment (GCash / PayMaya)
            </h2>
            <div class="space-y-4">
                <div>
                    <label for="gcash_number" class="text-sm font-medium text-gray-600 mb-1 block">Payment Number</label>
                    <input type="text" id="gcash_number" name="gcash_number" value="{{ old('gcash_number', $settings->gcash_number ?? '09925247231') }}" required maxlength="20"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-300 focus:border-yellow-400 outline-none transition bg-white/50 text-sm font-mono text-lg"
                           placeholder="09925247231">
                    <p class="text-xs text-gray-400 mt-1">This number is shown to customers for GCash/PayMaya payments</p>
                </div>
                <div>
                    <label for="qr_code" class="text-sm font-medium text-gray-600 mb-1 block">QR Code Image</label>
                    <div class="flex items-start gap-4">
                        @if($settings->qr_code_path)
                            <div class="flex-shrink-0">
                                <img src="{{ asset($settings->qr_code_path) }}" alt="Current QR" class="w-24 h-24 rounded-xl border border-gray-200 shadow-sm object-cover">
                            </div>
                        @endif
                        <div class="flex-1">
                            <input type="file" id="qr_code" name="qr_code" accept="image/png,image/jpeg,image/webp"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-300 focus:border-yellow-400 outline-none transition bg-white/50 text-sm file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-yellow-100 file:text-yellow-700 file:font-semibold file:text-xs">
                            <p class="text-xs text-gray-400 mt-1">PNG, JPG, or WebP. Max 2MB. Shown in customer payment modal.</p>
                        </div>
                    </div>
                </div>
                <div>
                    <label for="payment_instructions" class="text-sm font-medium text-gray-600 mb-1 block">Payment Instructions</label>
                    <textarea id="payment_instructions" name="payment_instructions" rows="2"
                              class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-300 focus:border-yellow-400 outline-none transition bg-white/50 text-sm resize-none"
                              placeholder="e.g. Scan QR → Pay → Enter reference number">{{ old('payment_instructions', $settings->payment_instructions ?? '') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <button type="submit"
                class="w-full bg-gradient-to-r from-sky-500 to-sky-600 text-white py-3 rounded-xl font-semibold hover:from-sky-600 hover:to-sky-700 transition shadow-lg shadow-sky-200 min-h-[44px] flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
            Save Settings
        </button>
    </form>
</div>
@endsection
