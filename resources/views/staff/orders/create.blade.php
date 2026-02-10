@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Create New Order</h1>

    <form method="POST" action="{{ route('staff.orders.store') }}" id="orderForm" class="space-y-6">
        @csrf

        {{-- Customer Selection --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Customer</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="phone_lookup" class="block text-sm font-medium text-gray-700 mb-1">Phone Lookup</label>
                    <input type="tel" id="phone_lookup" placeholder="Enter phone to find customer"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <p id="lookup_result" class="mt-1 text-xs text-gray-500"></p>
                </div>
                <div>
                    <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-1">Select Customer</label>
                    <select name="customer_id" id="customer_id" required
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">-- Choose Customer --</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}"
                                {{ old('customer_id', isset($repeatOrder) ? $repeatOrder->customer_id : '') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }} ({{ $customer->phone }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Order Items --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Items</h2>
                <button type="button" onclick="addItem()"
                    class="rounded-lg bg-indigo-50 px-4 py-2 text-sm font-medium text-indigo-700 hover:bg-indigo-100 transition">
                    + Add Item
                </button>
            </div>

            <div id="items-container">
                {{-- Pre-filled from repeat order or default first row --}}
            </div>

            <div class="mt-4 flex items-center justify-between border-t border-gray-100 pt-4">
                <span class="text-sm text-gray-500">Total Weight: <strong id="totalWeight">0.00</strong> kg</span>
                <span class="text-lg font-bold text-indigo-600">Total: ₱<span id="totalPrice">0.00</span></span>
            </div>
        </div>

        {{-- Notes --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes (optional)</label>
            <textarea name="notes" id="notes" rows="2"
                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                placeholder="Special instructions...">{{ old('notes') }}</textarea>
        </div>

        <button type="submit"
            class="w-full rounded-lg bg-indigo-600 px-6 py-3 text-sm font-semibold text-white hover:bg-indigo-700 transition">
            Create Order
        </button>
    </form>
</div>

@push('scripts')
<script>
    const PRICES = {
        wash: {{ $settings->wash_price }},
        dry:  {{ $settings->dry_price }},
        fold: {{ $settings->fold_price }},
    };

    let itemIndex = 0;

    function addItem(clothType = '', weight = '', serviceType = 'wash') {
        const container = document.getElementById('items-container');
        const html = `
        <div class="item-row grid grid-cols-1 sm:grid-cols-12 gap-3 mb-3 items-end" data-index="${itemIndex}">
            <div class="sm:col-span-4">
                <label class="block text-xs font-medium text-gray-500 mb-1">Cloth Type</label>
                <input type="text" name="items[${itemIndex}][cloth_type]" value="${clothType}" required placeholder="e.g. T-shirts, Jeans"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div class="sm:col-span-2">
                <label class="block text-xs font-medium text-gray-500 mb-1">Weight (kg)</label>
                <input type="number" name="items[${itemIndex}][weight]" value="${weight}" required min="0.1" step="0.1"
                    onchange="recalculate()" oninput="recalculate()" placeholder="0.0"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500 weight-input">
            </div>
            <div class="sm:col-span-3">
                <label class="block text-xs font-medium text-gray-500 mb-1">Service</label>
                <select name="items[${itemIndex}][service_type]" onchange="recalculate()"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500 service-select">
                    <option value="wash" ${serviceType === 'wash' ? 'selected' : ''}>Wash (₱${PRICES.wash}/kg)</option>
                    <option value="dry" ${serviceType === 'dry' ? 'selected' : ''}>Dry (₱${PRICES.dry}/kg)</option>
                    <option value="fold" ${serviceType === 'fold' ? 'selected' : ''}>Fold (₱${PRICES.fold}/kg)</option>
                </select>
            </div>
            <div class="sm:col-span-2 text-right">
                <label class="block text-xs font-medium text-gray-500 mb-1">Subtotal</label>
                <span class="item-subtotal text-sm font-semibold text-gray-900">₱0.00</span>
            </div>
            <div class="sm:col-span-1 text-right">
                <button type="button" onclick="removeItem(this)" class="text-red-400 hover:text-red-600 text-lg" title="Remove">&times;</button>
            </div>
        </div>`;
        container.insertAdjacentHTML('beforeend', html);
        itemIndex++;
        recalculate();
    }

    function removeItem(btn) {
        btn.closest('.item-row').remove();
        recalculate();
    }

    function recalculate() {
        let totalWeight = 0, totalPrice = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const weight = parseFloat(row.querySelector('.weight-input')?.value) || 0;
            const service = row.querySelector('.service-select')?.value || 'wash';
            const price = PRICES[service] || 0;
            const subtotal = weight * price;
            totalWeight += weight;
            totalPrice += subtotal;
            const subtotalEl = row.querySelector('.item-subtotal');
            if (subtotalEl) subtotalEl.textContent = '₱' + subtotal.toFixed(2);
        });
        document.getElementById('totalWeight').textContent = totalWeight.toFixed(2);
        document.getElementById('totalPrice').textContent = totalPrice.toFixed(2);
    }

    // Customer phone lookup
    const phoneLookup = document.getElementById('phone_lookup');
    if (phoneLookup) {
        let debounce;
        phoneLookup.addEventListener('input', function() {
            clearTimeout(debounce);
            debounce = setTimeout(async () => {
                const phone = this.value.trim();
                if (phone.length < 4) return;
                try {
                    const res = await fetch(`{{ route('staff.api.customer.lookup') }}?phone=${encodeURIComponent(phone)}`);
                    const data = await res.json();
                    const result = document.getElementById('lookup_result');
                    if (data.found) {
                        result.textContent = `Found: ${data.customer.name}`;
                        result.className = 'mt-1 text-xs text-green-600';
                        document.getElementById('customer_id').value = data.customer.id;
                    } else {
                        result.textContent = 'No customer found with this phone.';
                        result.className = 'mt-1 text-xs text-yellow-600';
                    }
                } catch (e) { /* silently fail */ }
            }, 400);
        });
    }

    // Initialize
    @if (isset($repeatOrder) && $repeatOrder->items->count())
        @foreach ($repeatOrder->items as $item)
            addItem('{{ $item->cloth_type }}', '{{ $item->weight }}', '{{ $item->service_type }}');
        @endforeach
    @else
        addItem();
    @endif
</script>
@endpush
@endsection
