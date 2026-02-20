<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::updateOrCreate(['id' => 1], [
            'shop_name'            => 'GeloWash Laundry Shop',
            'shop_address'         => 'Purok 3, Brgy. San Isidro, General Santos City',
            'shop_phone'           => '0960-720-4055',
            'wash_price'           => 25.00,
            'dry_price'            => 15.00,
            'fold_price'           => 10.00,
            'gcash_number'         => '09925247231',
            'payment_instructions' => 'Scan QR or send payment to the number above, then enter your reference number below.',
        ]);
    }
}
