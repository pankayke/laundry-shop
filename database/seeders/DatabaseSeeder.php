<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Services\TicketNumberService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 0. Permissions & Roles
        $permissions = [
            'orders.manage',
            'orders.view',
            'customers.view',
            'customers.manage',
            'reports.view',
            'settings.manage',
            'users.manage',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions($permissions);

        $staffRole = Role::firstOrCreate(['name' => 'staff']);
        $staffRole->syncPermissions(['orders.manage', 'customers.view']);

        $customerRole = Role::firstOrCreate(['name' => 'customer']);
        $customerRole->syncPermissions(['orders.view']);

        // 1. Settings
        $this->call(SettingSeeder::class);

        // 2. Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@gelowash.com'],
            [
                'name'     => 'Admin User',
                'phone'    => '09170000001',
                'password' => Hash::make('password'),
                'role'     => 'admin',
            ]
        );
        $admin->syncRoles(['admin']);

        // 3. Staff
        $staff1 = User::firstOrCreate(
            ['email' => 'staff@gelowash.com'],
            [
                'name'     => 'Maria Santos',
                'phone'    => '09170000002',
                'password' => Hash::make('password'),
                'role'     => 'staff',
            ]
        );
        $staff1->syncRoles(['staff']);

        $staff2 = User::firstOrCreate(
            ['email' => 'juan@gelowash.com'],
            [
                'name'     => 'Juan Reyes',
                'phone'    => '09170000003',
                'password' => Hash::make('password'),
                'role'     => 'staff',
            ]
        );
        $staff2->syncRoles(['staff']);

        // 4. Customers
        $customers = collect();
        $customerData = [
            ['name' => 'Ana Cruz',       'phone' => '09181111111', 'email' => 'ana@example.com'],
            ['name' => 'Ben Torres',     'phone' => '09182222222', 'email' => 'ben@example.com'],
            ['name' => 'Carla Diaz',     'phone' => '09183333333', 'email' => 'carla@example.com'],
            ['name' => 'Dennis Lim',     'phone' => '09184444444', 'email' => 'dennis@example.com'],
            ['name' => 'Elena Bautista', 'phone' => '09185555555', 'email' => 'elena@example.com'],
        ];

        foreach ($customerData as $data) {
            $customer = User::firstOrCreate(
                ['phone' => $data['phone']],
                [
                    'name'     => $data['name'],
                    'email'    => $data['email'],
                    'password' => Hash::make('password'),
                    'role'     => 'customer',
                ]
            );
            $customer->syncRoles(['customer']);
            $customers->push($customer);
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

        // 6. Pending Approval Orders (customer-submitted requests)
        $pendingServices = [
            ['wash', 'dry', 'fold'],
            ['wash', 'dry'],
            ['wash'],
            ['wash', 'fold'],
            ['dry', 'fold'],
        ];
        $pendingInstructions = [
            'Please handle with care, delicate fabrics.',
            'Separate whites from colored clothes.',
            null,
            'Use fabric softener please.',
            'Extra rinse cycle if possible.',
        ];

        for ($i = 0; $i < 5; $i++) {
            $customer = $customers[$i];
            $services = $pendingServices[$i];
            $weight = round(rand(15, 80) / 10, 1);
            $estimatedPrice = collect($services)->sum(fn($s) => $prices[$s]) * $weight;
            $createdAt = now()->subHours(rand(1, 48));

            Order::create([
                'ticket_number'       => $ticketService->generate(),
                'customer_id'         => $customer->id,
                'staff_id'            => null,
                'status'              => 'pending_approval',
                'total_weight'        => 0,
                'estimated_weight'    => $weight,
                'total_price'         => round($estimatedPrice, 2),
                'payment_status'      => 'unpaid',
                'payment_method'      => 'gcash',
                'payment_reference'   => 'GCASH' . str_pad(rand(100000, 999999), 6, '0'),
                'requested_services'  => $services,
                'special_instructions'=> $pendingInstructions[$i],
                'created_at'          => $createdAt,
                'updated_at'          => $createdAt,
            ]);
        }

        $this->command->info('Seeded: 1 admin, 2 staff, 5 customers, 20 orders, 5 pending requests');
    }
}
