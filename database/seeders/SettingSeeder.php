<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::updateOrCreate(['id' => 1], [
            'shop_name'    => 'FreshKlean Laundry',
            'shop_address' => '123 Main Street, Barangay Centro, Quezon City',
            'shop_phone'   => '09171234567',
            'wash_price'   => 25.00,
            'dry_price'    => 15.00,
            'fold_price'   => 10.00,
        ]);
    }
}
