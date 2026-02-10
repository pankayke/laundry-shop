<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'shop_name',
        'shop_address',
        'shop_phone',
        'wash_price',
        'dry_price',
        'fold_price',
    ];

    protected $casts = [
        'wash_price' => 'decimal:2',
        'dry_price'  => 'decimal:2',
        'fold_price' => 'decimal:2',
    ];

    /** Return the singleton settings row (auto-creates with defaults). */
    public static function instance(): self
    {
        return Cache::remember('app_settings', 3600, function () {
            return self::first() ?? self::create([
                'shop_name'    => 'My Laundry Shop',
                'shop_address' => '123 Main Street, Manila',
                'shop_phone'   => '09171234567',
                'wash_price'   => 25.00,
                'dry_price'    => 15.00,
                'fold_price'   => 10.00,
            ]);
        });
    }

    public static function clearCache(): void
    {
        Cache::forget('app_settings');
    }

    public function getPriceForService(string $serviceType): float
    {
        return match ($serviceType) {
            'wash'  => (float) $this->wash_price,
            'dry'   => (float) $this->dry_price,
            'fold'  => (float) $this->fold_price,
            default => 0.00,
        };
    }
}
