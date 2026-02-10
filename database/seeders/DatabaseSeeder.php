<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Services\TicketNumberService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Settings
        $this->call(SettingSeeder::class);

        // 2. Admin
        $admin = User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@laundry.test',
            'phone'    => '09170000001',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // 3. Staff
        $staff1 = User::create([
            'name'     => 'Maria Santos',
            'email'    => 'maria@laundry.test',
            'phone'    => '09170000002',
            'password' => Hash::make('password'),
            'role'     => 'staff',
        ]);

        $staff2 = User::create([
            'name'     => 'Juan Reyes',
            'email'    => 'juan@laundry.test',
            'phone'    => '09170000003',
            'password' => Hash::make('password'),
            'role'     => 'staff',
        ]);

        // 4. Customers
        $customers = collect();
        $customerData = [
            ['name' => 'Ana Cruz',      'phone' => '09181111111'],
            ['name' => 'Ben Torres',    'phone' => '09182222222'],
            ['name' => 'Carla Diaz',    'phone' => '09183333333'],
            ['name' => 'Dennis Lim',    'phone' => '09184444444'],
            ['name' => 'Elena Bautista','phone' => '09185555555'],
        ];

        foreach ($customerData as $data) {
            $customers->push(User::create([
                'name'     => $data['name'],
                'phone'    => $data['phone'],
                'password' => Hash::make('password'),
                'role'     => 'customer',
            ]));
        }

        // 5. Orders (20 sample orders spread across last 30 days)
        $ticketService = new TicketNumberService();
        $staffMembers = [$staff1, $staff2];
        $statuses = ['received', 'washing', 'drying', 'folding', 'ready_for_pickup', 'collected'];
        $paymentMethods = ['cash', 'gcash', 'maya'];
        $clothTypes = ['T-Shirts', 'Jeans', 'Bed Sheets', 'Towels', 'Uniforms', 'Blankets', 'Curtains', 'Polo Shirts', 'Socks & Underwear', 'Jackets'];
        $serviceTypes = ['wash', 'dry', 'fold'];
        $prices = ['wash' => 25.00, 'dry' => 15.00, 'fold' => 10.00];

        for ($i = 0; $i < 20; $i++) {
            $customer = $customers->random();
            $staff = $staffMembers[array_rand($staffMembers)];
            $status = $statuses[array_rand($statuses)];
            $createdAt = now()->subDays(rand(0, 30))->subHours(rand(0, 12));

            $order = Order::create([
                'ticket_number'  => $ticketService->generate(),
                'customer_id'    => $customer->id,
                'staff_id'       => $staff->id,
                'status'         => $status,
                'total_weight'   => 0,
                'total_price'    => 0,
                'payment_status' => in_array($status, ['ready_for_pickup', 'collected']) ? 'paid' : (rand(0, 1) ? 'paid' : 'unpaid'),
                'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                'created_at'     => $createdAt,
                'updated_at'     => $createdAt,
            ]);

            // 1-4 items per order
            $itemCount = rand(1, 4);
            $totalWeight = 0;
            $totalPrice = 0;

            for ($j = 0; $j < $itemCount; $j++) {
                $clothType = $clothTypes[array_rand($clothTypes)];
                $serviceType = $serviceTypes[array_rand($serviceTypes)];
                $weight = round(rand(10, 80) / 10, 1); // 1.0 – 8.0 kg
                $pricePerKg = $prices[$serviceType];
                $subtotal = round($weight * $pricePerKg, 2);

                OrderItem::create([
                    'order_id'     => $order->id,
                    'cloth_type'   => $clothType,
                    'weight'       => $weight,
                    'service_type' => $serviceType,
                    'price_per_kg' => $pricePerKg,
                    'subtotal'     => $subtotal,
                ]);

                $totalWeight += $weight;
                $totalPrice += $subtotal;
            }

            $order->update([
                'total_weight' => round($totalWeight, 2),
                'total_price'  => round($totalPrice, 2),
                'amount_paid'  => $order->payment_status === 'paid' ? round($totalPrice, 2) : 0,
            ]);
        }

        $this->command->info('Seeded: 1 admin, 2 staff, 5 customers, 20 orders');
    }
}
