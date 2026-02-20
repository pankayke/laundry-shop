<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentOrdersTable extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Recent Orders')
            ->query(Order::query()->with('customer')->latest()->limit(10))
            ->columns([
                Tables\Columns\TextColumn::make('ticket_number')
                    ->weight('bold')
                    ->searchable(),

                Tables\Columns\TextColumn::make('customer.name')
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning'   => 'received',
                        'info'      => fn ($state) => in_array($state, ['washing', 'drying', 'folding']),
                        'success'   => 'ready_for_pickup',
                        'secondary' => 'collected',
                    ])
                    ->formatStateUsing(fn ($state) => Order::STATUSES[$state] ?? $state),

                Tables\Columns\TextColumn::make('total_price')
                    ->money('PHP'),

                Tables\Columns\BadgeColumn::make('payment_status')
                    ->colors([
                        'danger'  => 'unpaid',
                        'success' => 'paid',
                    ]),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M d, h:i A'),
            ])
            ->paginated(false);
    }
}
