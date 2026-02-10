@extends('layouts.app')
@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-3">
    <h1 class="text-2xl font-bold text-gray-900">Sales Report</h1>
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.sales.exportPdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
            class="rounded-lg bg-red-50 px-4 py-2 text-sm font-medium text-red-700 hover:bg-red-100 transition">
            📄 Export PDF
        </a>
        <a href="{{ route('admin.sales.exportCsv', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
            class="rounded-lg bg-green-50 px-4 py-2 text-sm font-medium text-green-700 hover:bg-green-100 transition">
            📊 Export CSV
        </a>
    </div>
</div>

{{-- Date Filter --}}
<form method="GET" action="{{ route('admin.sales') }}" class="flex flex-col sm:flex-row gap-3 mb-6">
    <div>
        <label class="block text-xs font-medium text-gray-500 mb-1">Start Date</label>
        <input type="date" name="start_date" value="{{ $startDate }}"
            class="rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500">
    </div>
    <div>
        <label class="block text-xs font-medium text-gray-500 mb-1">End Date</label>
        <input type="date" name="end_date" value="{{ $endDate }}"
            class="rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500">
    </div>
    <div class="flex items-end">
        <button type="submit"
            class="rounded-lg bg-indigo-600 px-6 py-2 text-sm font-semibold text-white hover:bg-indigo-700 transition">
            Filter
        </button>
    </div>
</form>

{{-- Metrics --}}
<div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-xs font-medium text-gray-500 uppercase">Total Orders</p>
        <p class="mt-1 text-2xl font-bold text-gray-900">{{ $metrics['total_orders'] }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-xs font-medium text-gray-500 uppercase">Total Revenue</p>
        <p class="mt-1 text-2xl font-bold text-green-600">₱{{ number_format($metrics['total_revenue'], 2) }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-xs font-medium text-gray-500 uppercase">Unpaid</p>
        <p class="mt-1 text-2xl font-bold text-red-500">₱{{ number_format($metrics['unpaid_total'], 2) }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-xs font-medium text-gray-500 uppercase">Cash</p>
        <p class="mt-1 text-xl font-bold text-gray-900">₱{{ number_format($metrics['cash_total'], 2) }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-xs font-medium text-gray-500 uppercase">GCash</p>
        <p class="mt-1 text-xl font-bold text-blue-600">₱{{ number_format($metrics['gcash_total'], 2) }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-xs font-medium text-gray-500 uppercase">Maya</p>
        <p class="mt-1 text-xl font-bold text-green-600">₱{{ number_format($metrics['maya_total'], 2) }}</p>
    </div>
</div>

{{-- Orders Table --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">
                    <th class="px-4 py-3">Ticket</th>
                    <th class="px-4 py-3">Customer</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Weight</th>
                    <th class="px-4 py-3 text-right">Total</th>
                    <th class="px-4 py-3">Payment</th>
                    <th class="px-4 py-3">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-indigo-600">{{ $order->ticket_number }}</td>
                        <td class="px-4 py-3">{{ $order->customer->name }}</td>
                        <td class="px-4 py-3">@include('components.status-badge', ['status' => $order->status])</td>
                        <td class="px-4 py-3 text-right">{{ $order->total_weight }} kg</td>
                        <td class="px-4 py-3 text-right font-medium">₱{{ number_format($order->total_price, 2) }}</td>
                        <td class="px-4 py-3">
                            <span class="{{ $order->isPaid() ? 'text-green-600' : 'text-red-500' }} text-xs font-medium">
                                {{ $order->isPaid() ? $order->payment_method_label : 'Unpaid' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-500">{{ $order->created_at->format('M d, Y h:i A') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">No orders in this date range.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
