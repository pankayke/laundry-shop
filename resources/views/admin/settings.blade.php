@extends('layouts.app')
@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Shop Settings</h1>

    <form method="POST" action="{{ route('admin.settings.update') }}"
        class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
        @csrf @method('PUT')

        <fieldset>
            <legend class="text-lg font-semibold text-gray-800 mb-4">Shop Information</legend>
            <div class="space-y-4">
                <div>
                    <label for="shop_name" class="block text-sm font-medium text-gray-700 mb-1">Shop Name</label>
                    <input id="shop_name" type="text" name="shop_name" value="{{ old('shop_name', $settings->shop_name) }}" required
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="shop_address" class="block text-sm font-medium text-gray-700 mb-1">Shop Address</label>
                    <textarea id="shop_address" name="shop_address" rows="2" required
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('shop_address', $settings->shop_address) }}</textarea>
                </div>
                <div>
                    <label for="shop_phone" class="block text-sm font-medium text-gray-700 mb-1">Shop Phone</label>
                    <input id="shop_phone" type="tel" name="shop_phone" value="{{ old('shop_phone', $settings->shop_phone) }}" required
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend class="text-lg font-semibold text-gray-800 mb-4">Pricing (₱ per kg)</legend>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="wash_price" class="block text-sm font-medium text-gray-700 mb-1">🫧 Wash Price</label>
                    <input id="wash_price" type="number" name="wash_price" step="0.01" min="0"
                        value="{{ old('wash_price', $settings->wash_price) }}" required
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="dry_price" class="block text-sm font-medium text-gray-700 mb-1">☀️ Dry Price</label>
                    <input id="dry_price" type="number" name="dry_price" step="0.01" min="0"
                        value="{{ old('dry_price', $settings->dry_price) }}" required
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="fold_price" class="block text-sm font-medium text-gray-700 mb-1">👕 Fold Price</label>
                    <input id="fold_price" type="number" name="fold_price" step="0.01" min="0"
                        value="{{ old('fold_price', $settings->fold_price) }}" required
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>
        </fieldset>

        <button type="submit"
            class="w-full rounded-lg bg-indigo-600 px-6 py-3 text-sm font-semibold text-white hover:bg-indigo-700 transition">
            Save Settings
        </button>
    </form>
</div>
@endsection
