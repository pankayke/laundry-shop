<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function afterSave(): void
    {
        $order = $this->record;
        $order->load('items');
        $totalWeight = $order->items->sum('weight');
        $totalPrice = $order->items->sum('subtotal');

        $order->update([
            'total_weight' => round($totalWeight, 2),
            'total_price'  => round($totalPrice, 2),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
