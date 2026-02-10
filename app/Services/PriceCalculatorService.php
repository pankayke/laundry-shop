<?php

namespace App\Services;

use App\Models\Setting;

class PriceCalculatorService
{
    public function calculateItemSubtotal(string $serviceType, float $weight): array
    {
        $settings   = Setting::instance();
        $pricePerKg = $settings->getPriceForService($serviceType);
        $subtotal   = round($pricePerKg * $weight, 2);

        return [
            'price_per_kg' => $pricePerKg,
            'subtotal'     => $subtotal,
        ];
    }

    public function calculateOrderTotal(array $items): array
    {
        $totalWeight = 0;
        $totalPrice  = 0;

        foreach ($items as $item) {
            $totalWeight += (float) ($item['weight'] ?? 0);
            $totalPrice  += (float) ($item['subtotal'] ?? 0);
        }

        return [
            'total_weight' => round($totalWeight, 2),
            'total_price'  => round($totalPrice, 2),
        ];
    }
}
