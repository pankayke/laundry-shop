<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class RevenueByMethodChart extends ChartWidget
{
    protected static ?string $heading = 'Revenue by Payment Method';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $paid = Order::where('payment_status', 'paid');

        $cash  = (clone $paid)->where('payment_method', 'cash')->sum('total_price');
        $gcash = (clone $paid)->where('payment_method', 'gcash')->sum('total_price');
        $maya  = (clone $paid)->where('payment_method', 'maya')->sum('total_price');

        return [
            'datasets' => [
                [
                    'data' => [$cash, $gcash, $maya],
                    'backgroundColor' => ['#22c55e', '#3b82f6', '#a855f7'],
                ],
            ],
            'labels' => ['Cash', 'GCash', 'Maya'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
