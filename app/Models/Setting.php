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
        'gcash_number',
        'qr_code_path',
        'payment_instructions',
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
                'shop_name'            => 'GeloWash Laundry Shop',
                'shop_address'         => 'Purok 3, Brgy. San Isidro, General Santos City',
                'shop_phone'           => '0960-720-4055',
                'wash_price'           => 25.00,
                'dry_price'            => 15.00,
                'fold_price'           => 10.00,
                'gcash_number'         => '09925247231',
                'payment_instructions' => 'Scan QR or send payment to the number above, then enter your reference number below.',
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
