@extends('layouts.app')
@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-3">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Staff Dashboard</h1>
        <p class="text-sm text-gray-500">{{ now()->format('l, F d, Y') }}</p>
    </div>
    <a href="{{ route('staff.orders.create') }}"
        class="inline-flex items-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 transition">
        + New Order
    </a>
</div>

{{-- Stats Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Orders Today</p>
        <p class="mt-1 text-2xl font-bold text-gray-900">{{ $todayStats['total_orders'] }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Revenue Today</p>
        <p class="mt-1 text-2xl font-bold text-green-600">₱{{ number_format($todayStats['total_revenue'], 2) }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">In Progress</p>
        <p class="mt-1 text-2xl font-bold text-yellow-600">{{ $todayStats['pending_count'] }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Ready for Pickup</p>
        <p class="mt-1 text-2xl font-bold text-indigo-600">{{ $todayStats['ready_count'] }}</p>
    </div>
</div>

{{-- Received Today --}}
<section class="mb-8">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">📥 Received Today ({{ $receivedToday->count() }})</h2>
    @forelse ($receivedToday as $order)
        @include('staff._order-card', ['order' => $order])
    @empty
        <p class="text-sm text-gray-400 bg-white rounded-lg p-4 border border-gray-100">No orders received today.</p>
    @endforelse
</section>

{{-- Pending (Washing / Drying / Folding) --}}
<section class="mb-8">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">🔄 In Progress ({{ $pendingOrders->count() }})</h2>
    @forelse ($pendingOrders as $order)
        @include('staff._order-card', ['order' => $order])
    @empty
        <p class="text-sm text-gray-400 bg-white rounded-lg p-4 border border-gray-100">No pending orders.</p>
    @endforelse
</section>

{{-- Ready for Pickup --}}
<section>
    <h2 class="text-lg font-semibold text-gray-800 mb-4">✅ Ready for Pickup ({{ $readyForPickup->count() }})</h2>
    @forelse ($readyForPickup as $order)
        @include('staff._order-card', ['order' => $order])
    @empty
        <p class="text-sm text-gray-400 bg-white rounded-lg p-4 border border-gray-100">No orders ready for pickup.</p>
    @endforelse
</section>
@endsection
