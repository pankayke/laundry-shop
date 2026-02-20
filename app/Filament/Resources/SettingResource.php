<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Shop Settings';

    protected static ?int $navigationSort = 10;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Shop Information')->schema([
                Forms\Components\TextInput::make('shop_name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('shop_address')
                    ->required()
                    ->maxLength(500),

                Forms\Components\TextInput::make('shop_phone')
                    ->tel()
                    ->required()
                    ->maxLength(20),
            ])->columns(1),

            Forms\Components\Section::make('Pricing (₱ per kg)')->schema([
                Forms\Components\TextInput::make('wash_price')
                    ->numeric()
                    ->required()
                    ->prefix('₱')
                    ->minValue(0)
                    ->label('Wash Price / kg'),

                Forms\Components\TextInput::make('dry_price')
                    ->numeric()
                    ->required()
                    ->prefix('₱')
                    ->minValue(0)
                    ->label('Dry Price / kg'),

                Forms\Components\TextInput::make('fold_price')
                    ->numeric()
                    ->required()
                    ->prefix('₱')
                    ->minValue(0)
                    ->label('Fold Price / kg'),
            ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('shop_name')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('shop_phone'),

                Tables\Columns\TextColumn::make('wash_price')
                    ->prefix('₱')
                    ->label('Wash/kg'),

                Tables\Columns\TextColumn::make('dry_price')
                    ->prefix('₱')
                    ->label('Dry/kg'),

                Tables\Columns\TextColumn::make('fold_price')
                    ->prefix('₱')
                    ->label('Fold/kg'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSettings::route('/'),
            'edit'  => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}
