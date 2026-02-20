@extends('layouts.admin')

@section('title', 'New Order – GeloWash')

@section('content')
<div class="max-w-4xl mx-auto space-y-6"
     x-data="orderForm()"
     x-init="init()">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">New Order</h1>
            <p class="text-gray-400 text-sm mt-1">Create a new laundry order</p>
        </div>
        <a href="{{ route('staff.dashboard') }}" class="text-sm text-gray-500 hover:text-sky-600 transition flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back
        </a>
    </div>

    <form method="POST" action="{{ route('staff.orders.store') }}" class="space-y-6">
        @csrf

        {{-- Customer Selection --}}
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl p-6">
            <h2 class="text-lg font-bold text-gray-700 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-sky-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0"/></svg>
                Customer
            </h2>
            <select name="customer_id" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-sky-300 focus:border-sky-400 outline-none transition bg-white/50 text-sm">
                <option value="">Select customer...</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}"
                        {{ (old('customer_id') == $customer->id || (isset($repeatOrder) && $repeatOrder->customer_id == $customer->id)) ? 'selected' : '' }}>
                        {{ $customer->name }} {{ $customer->phone ? "({$customer->phone})" : '' }}
                    </option>
                @endforeach
            </select>
            @error('customer_id')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Order Items --}}
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-700 flex items-center gap-2">
                    <svg class="w-5 h-5 text-sky-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><circle cx="12" cy="13" r="4"/><rect x="8" y="5" width="8" height="4" rx="2"/></svg>
                    Laundry Items
                </h2>
                <button type="button" @click="addItem()"
                        class="inline-flex items-center gap-1 bg-sky-100 text-sky-700 px-3 py-2 rounded-xl text-sm font-semibold hover:bg-sky-200 transition min-h-[40px]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
                    Add Item
                </button>
            </div>

            <div class="space-y-4">
                <template x-for="(item, index) in items" :key="index">
                    <div class="bg-sky-50/50 rounded-xl p-4 relative">
                        <button type="button" @click="removeItem(index)" x-show="items.length > 1"
                                class="absolute top-2 right-2 text-red-400 hover:text-red-600 p-1 rounded-lg hover:bg-red-50 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">Cloth Type</label>
                                <input type="text" :name="'items['+index+'][cloth_type]'" x-model="item.cloth_type" required
                                       class="w-full px-3 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-sky-300 focus:border-sky-400 outline-none transition text-sm bg-white"
                                       placeholder="e.g., Shirts, Pants">
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">Weight (kg)</label>
                                <input type="number" :name="'items['+index+'][weight]'" x-model="item.weight" step="0.1" min="0.1" required
                                       @input="calculateTotal()"
                                       class="w-full px-3 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-sky-300 focus:border-sky-400 outline-none transition text-sm bg-white"
                                       placeholder="0.0">
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">Service</label>
                                <select :name="'items['+index+'][service_type]'" x-model="item.service_type" required
                                        @change="calculateTotal()"
                                        class="w-full px-3 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-sky-300 focus:border-sky-400 outline-none transition text-sm bg-white">
                                    <option value="wash">Wash – ₱{{ number_format($settings->wash_price, 2) }}/kg</option>
                                    <option value="dry">Dry – ₱{{ number_format($settings->dry_price, 2) }}/kg</option>
                                    <option value="fold">Fold – ₱{{ number_format($settings->fold_price, 2) }}/kg</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-2 text-right">
                            <span class="text-xs text-gray-500">Subtotal: </span>
                            <span class="text-sm font-bold text-sky-600" x-text="'₱' + getSubtotal(item).toFixed(2)"></span>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Total --}}
            <div class="mt-4 flex justify-end">
                <div class="bg-gradient-to-r from-sky-500 to-sky-600 text-white px-6 py-3 rounded-xl shadow-lg">
                    <span class="text-sm opacity-80">Estimated Total</span>
                    <p class="text-xl font-bold" x-text="'₱' + total.toFixed(2)"></p>
                </div>
            </div>
        </div>

        {{-- Notes --}}
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl p-6">
            <h2 class="text-lg font-bold text-gray-700 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-sky-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                Notes (optional)
            </h2>
            <textarea name="notes" rows="3"
                      class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-sky-300 focus:border-sky-400 outline-none transition bg-white/50 text-sm resize-none"
                      placeholder="Special instructions, preferred detergent, etc.">{{ old('notes') }}</textarea>
        </div>

        {{-- Submit --}}
        <div class="flex gap-3">
            <button type="submit"
                    class="flex-1 bg-gradient-to-r from-sky-500 to-sky-600 text-white py-3 rounded-xl font-semibold hover:from-sky-600 hover:to-sky-700 transition shadow-lg shadow-sky-200 min-h-[44px] flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                Create Order
            </button>
            <a href="{{ route('staff.dashboard') }}"
               class="px-6 py-3 rounded-xl font-semibold border border-gray-200 text-gray-600 hover:bg-gray-50 transition min-h-[44px] flex items-center">
                Cancel
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
function orderForm() {
    return {
        items: [],
        total: 0,
        prices: {
            wash: {{ $settings->wash_price }},
            dry: {{ $settings->dry_price }},
            fold: {{ $settings->fold_price }},
        },
        init() {
            @if(isset($repeatOrder) && $repeatOrder->items)
                @foreach($repeatOrder->items as $item)
                    this.items.push({
                        cloth_type: '{{ $item->cloth_type }}',
                        weight: {{ $item->weight }},
                        service_type: '{{ $item->service_type }}',
                    });
                @endforeach
            @else
                this.items.push({ cloth_type: '', weight: '', service_type: 'wash' });
            @endif
            this.calculateTotal();
        },
        addItem() {
            this.items.push({ cloth_type: '', weight: '', service_type: 'wash' });
        },
        removeItem(index) {
            this.items.splice(index, 1);
            this.calculateTotal();
        },
        getSubtotal(item) {
            const weight = parseFloat(item.weight) || 0;
            return weight * (this.prices[item.service_type] || 0);
        },
        calculateTotal() {
            this.total = this.items.reduce((sum, item) => sum + this.getSubtotal(item), 0);
        },
    };
}
</script>
@endpush
@endsection
