@extends('layouts.admin')

@section('title', 'Sales Report – GeloWash')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Sales Report</h1>
            <p class="text-gray-400 text-sm mt-1">Filter and export your sales data</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.sales.exportPdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
               class="inline-flex items-center gap-2 bg-red-100 text-red-700 px-4 py-2.5 rounded-xl font-semibold hover:bg-red-200 transition min-h-[44px]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                PDF
            </a>
            <a href="{{ route('admin.sales.exportCsv', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
               class="inline-flex items-center gap-2 bg-green-100 text-green-700 px-4 py-2.5 rounded-xl font-semibold hover:bg-green-200 transition min-h-[44px]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                CSV
            </a>
        </div>
    </div>

    {{-- Date Filter --}}
    <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl p-6">
        <form method="GET" action="{{ route('admin.sales') }}" class="flex flex-col sm:flex-row gap-3 items-end">
            <div class="flex-1">
                <label class="text-sm font-medium text-gray-600 block mb-1">Start Date</label>
                <input type="date" name="start_date" value="{{ $startDate }}"
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-sky-300 focus:border-sky-400 outline-none transition bg-white/50 text-sm">
            </div>
            <div class="flex-1">
                <label class="text-sm font-medium text-gray-600 block mb-1">End Date</label>
                <input type="date" name="end_date" value="{{ $endDate }}"
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-sky-300 focus:border-sky-400 outline-none transition bg-white/50 text-sm">
            </div>
            <button type="submit" class="bg-gradient-to-r from-sky-500 to-sky-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-sky-600 hover:to-sky-700 transition shadow-lg shadow-sky-200 min-h-[44px]">
                Filter
            </button>
        </form>
    </div>

    {{-- Metrics --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl p-5">
            <p class="text-xs text-gray-400 mb-1">Total Orders</p>
            <p class="text-2xl font-bold text-gray-800">{{ number_format($metrics['total_orders']) }}</p>
        </div>
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl p-5">
            <p class="text-xs text-gray-400 mb-1">Total Revenue</p>
            <p class="text-2xl font-bold text-green-600">₱{{ number_format($metrics['total_revenue'], 2) }}</p>
        </div>
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl p-5">
            <p class="text-xs text-gray-400 mb-1">Unpaid</p>
            <p class="text-2xl font-bold text-amber-600">₱{{ number_format($metrics['unpaid_total'] ?? 0, 2) }}</p>
        </div>
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl p-5">
            <p class="text-xs text-gray-400 mb-1 flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-green-500"></span> Cash</p>
            <p class="text-xl font-bold text-gray-700">₱{{ number_format($metrics['cash_total'], 2) }}</p>
        </div>
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl p-5">
            <p class="text-xs text-gray-400 mb-1 flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-blue-500"></span> GCash</p>
            <p class="text-xl font-bold text-gray-700">₱{{ number_format($metrics['gcash_total'], 2) }}</p>
        </div>
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl p-5">
            <p class="text-xs text-gray-400 mb-1 flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-purple-500"></span> Maya</p>
            <p class="text-xl font-bold text-gray-700">₱{{ number_format($metrics['maya_total'], 2) }}</p>
        </div>
    </div>

    {{-- Orders Table --}}
    <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100">
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Ticket</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Customer</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Status</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">Weight</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">Total</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Payment</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-sky-50/50 transition">
                            <td class="px-4 py-3 font-mono font-bold text-sky-600">{{ $order->ticket_number }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $order->customer->name ?? 'Walk-in' }}</td>
                            <td class="px-4 py-3">@include('components.status-badge', ['status' => $order->status])</td>
                            <td class="px-4 py-3 text-right text-gray-600">{{ number_format($order->total_weight, 2) }} kg</td>
                            <td class="px-4 py-3 text-right font-semibold text-gray-700">₱{{ number_format($order->total_price, 2) }}</td>
                            <td class="px-4 py-3">
                                <span class="{{ $order->payment_status === 'paid' ? 'text-green-600' : 'text-amber-600' }} font-medium">
                                    {{ ucfirst($order->payment_status) }}
                                    @if($order->payment_method && $order->payment_method !== 'unpaid')
                                        ({{ ucfirst($order->payment_method) }})
                                    @endif
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ $order->created_at->format('M d, g:ia') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-400">No orders in this period</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
