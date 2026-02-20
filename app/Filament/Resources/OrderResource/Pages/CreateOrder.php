<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Services\TicketNumberService;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['ticket_number'] = (new TicketNumberService())->generate();
        $data['staff_id'] = auth()->id();

        return $data;
    }

    protected function afterCreate(): void
    {
        // Recalculate totals from items
        $order = $this->record;
        $order->load('items');
        $totalWeight = $order->items->sum('weight');
        $totalPrice = $order->items->sum('subtotal');

        $order->update([
            'total_weight' => round($totalWeight, 2),
            'total_price'  => round($totalPrice, 2),
        ]);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
