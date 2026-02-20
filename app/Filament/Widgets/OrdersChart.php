<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class OrdersChart extends ChartWidget
{
    protected static ?string $heading = 'Orders This Week';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $days = collect(range(6, 0))->map(fn ($d) => Carbon::today()->subDays($d));

        $counts = $days->map(fn ($day) => Order::whereDate('created_at', $day)->count());

        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data'  => $counts->toArray(),
                    'backgroundColor' => 'rgba(135, 206, 235, 0.5)',
                    'borderColor' => '#87CEEB',
                ],
            ],
            'labels' => $days->map(fn ($d) => $d->format('M d'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
