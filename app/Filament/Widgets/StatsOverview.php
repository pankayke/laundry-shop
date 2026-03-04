<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $today = now()->startOfDay();
        $paidToday = Order::whereDate('created_at', $today)->where('payment_status', 'paid');

        return [
            Stat::make('Orders Today', Order::whereDate('created_at', $today)->count())
                ->description('Total orders received today')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('primary'),

            Stat::make('Revenue Today', '₱' . number_format((clone $paidToday)->sum('total_price'), 2))
                ->description('From paid orders')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Pending Orders', Order::whereNotIn('status', ['collected', 'cancelled'])->count())
                ->description('Not yet collected')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Total Customers', User::where('role', 'customer')->count())
                ->description('Registered customers')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),
        ];
    }
}
