<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Jobs\SendOrderReadyNotification;
use App\Models\Order;
use App\Models\Setting;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Orders';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        $settings = Setting::instance();

        return $form->schema([
            Forms\Components\Section::make('Order Details')->schema([
                Forms\Components\Select::make('customer_id')
                    ->relationship('customer', 'name', fn (Builder $query) => $query->where('role', 'customer'))
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('status')
                    ->options(Order::STATUSES)
                    ->default('received')
                    ->required()
                    ->native(false),

                Forms\Components\Select::make('payment_status')
                    ->options([
                        'unpaid' => 'Unpaid',
                        'paid'   => 'Paid',
                    ])
                    ->default('unpaid')
                    ->required()
                    ->native(false),

                Forms\Components\Select::make('payment_method')
                    ->options(Order::PAYMENT_METHODS)
                    ->default('unpaid')
                    ->native(false),

                Forms\Components\Textarea::make('notes')
                    ->maxLength(1000)
                    ->columnSpanFull(),
            ])->columns(2),

            Forms\Components\Section::make('Order Items')->schema([
                Forms\Components\Repeater::make('items')
                    ->relationship()
                    ->schema([
                        Forms\Components\TextInput::make('cloth_type')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('weight')
                            ->numeric()
                            ->required()
                            ->minValue(0.1)
                            ->suffix('kg'),

                        Forms\Components\Select::make('service_type')
                            ->options([
                                'wash' => 'Wash (₱' . number_format($settings->wash_price, 2) . '/kg)',
                                'dry'  => 'Dry (₱' . number_format($settings->dry_price, 2) . '/kg)',
                                'fold' => 'Fold (₱' . number_format($settings->fold_price, 2) . '/kg)',
                            ])
                            ->required()
                            ->native(false)
                            ->reactive()
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) use ($settings) {
                                $price = $settings->getPriceForService($state ?? 'wash');
                                $set('price_per_kg', $price);
                                $weight = (float) ($get('weight') ?? 0);
                                $set('subtotal', round($price * $weight, 2));
                            }),

                        Forms\Components\TextInput::make('price_per_kg')
                            ->numeric()
                            ->required()
                            ->prefix('₱')
                            ->default($settings->wash_price),

                        Forms\Components\TextInput::make('subtotal')
                            ->numeric()
                            ->required()
                            ->prefix('₱')
                            ->default(0),
                    ])
                    ->columns(5)
                    ->defaultItems(1)
                    ->addActionLabel('+ Add Item')
                    ->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ticket_number')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable(),

                Tables\Columns\TextColumn::make('customer.name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('customer.phone')
                    ->label('Phone')
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning'   => 'received',
                        'info'      => fn ($state) => in_array($state, ['washing', 'drying', 'folding']),
                        'success'   => 'ready_for_pickup',
                        'secondary' => 'collected',
                    ])
                    ->formatStateUsing(fn ($state) => Order::STATUSES[$state] ?? $state),

                Tables\Columns\TextColumn::make('total_weight')
                    ->suffix(' kg')
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_price')
                    ->money('PHP')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('payment_status')
                    ->colors([
                        'danger'  => 'unpaid',
                        'success' => 'paid',
                    ]),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M d, Y h:i A')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(Order::STATUSES),

                Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        'unpaid' => 'Unpaid',
                        'paid'   => 'Paid',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('updateStatus')
                    ->label('Status')
                    ->icon('heroicon-o-arrow-path')
                    ->form([
                        Forms\Components\Select::make('status')
                            ->options(Order::STATUSES)
                            ->required()
                            ->native(false),
                    ])
                    ->action(function (Order $record, array $data): void {
                        $oldStatus = $record->status;
                        $record->update(['status' => $data['status']]);

                        if ($data['status'] === 'ready_for_pickup' && $oldStatus !== 'ready_for_pickup') {
                            SendOrderReadyNotification::dispatch($record);
                        }
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit'   => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
