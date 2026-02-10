@extends('layouts.app')
@section('content')
<div class="max-w-5xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Search Orders</h1>

    {{-- Search / Filter --}}
    <form method="GET" action="{{ route('staff.orders.search') }}" class="flex flex-col sm:flex-row gap-3 mb-6">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Ticket #, customer name, or phone"
            class="flex-1 rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
        <select name="status" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">All Statuses</option>
            @foreach (\App\Models\Order::STATUSES as $key => $label)
                <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <button type="submit"
            class="rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 transition">
            Search
        </button>
    </form>

    {{-- Results --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">
                        <th class="px-4 py-3">Ticket #</th>
                        <th class="px-4 py-3">Customer</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Total</th>
                        <th class="px-4 py-3">Payment</th>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-indigo-600">
                                <a href="{{ route('staff.orders.edit', $order) }}" class="hover:underline">{{ $order->ticket_number }}</a>
                            </td>
                            <td class="px-4 py-3">
                                {{ $order->customer->name }}
                                <span class="text-gray-400 text-xs block">{{ $order->customer->phone }}</span>
                            </td>
                            <td class="px-4 py-3">@include('components.status-badge', ['status' => $order->status])</td>
                            <td class="px-4 py-3 text-right font-medium">₱{{ number_format($order->total_price, 2) }}</td>
                            <td class="px-4 py-3">
                                <span class="{{ $order->isPaid() ? 'text-green-600' : 'text-red-500' }} text-xs font-medium">
                                    {{ $order->isPaid() ? $order->payment_method_label : 'Unpaid' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500 text-xs">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('staff.orders.edit', $order) }}"
                                    class="text-indigo-600 hover:text-indigo-800 text-xs font-medium">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-400">No orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $orders->links() }}
    </div>
</div>
@endsection
