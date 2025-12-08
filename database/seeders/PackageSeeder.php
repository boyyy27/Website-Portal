<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $packages = [
            [
                'name' => 'Starter',
                'description' => 'Cocok untuk bisnis kecil',
                'price' => 2500000,
                'duration_days' => 30,
                'is_active' => true,
                'features' => [
                    ['text' => 'Hingga 5 user', 'included' => true],
                    ['text' => '1.000 shipment/bulan', 'included' => true],
                    ['text' => 'Order Management', 'included' => true],
                    ['text' => 'Basic Tracking', 'included' => true],
                    ['text' => 'Email Support', 'included' => true],
                    ['text' => 'API Access', 'included' => false],
                    ['text' => 'Custom Integration', 'included' => false],
                ],
            ],
            [
                'name' => 'Professional',
                'description' => 'Solusi lengkap untuk bisnis menengah',
                'price' => 5000000,
                'duration_days' => 30,
                'is_active' => true,
                'features' => [
                    ['text' => 'Hingga 20 user', 'included' => true],
                    ['text' => '10.000 shipment/bulan', 'included' => true],
                    ['text' => 'Full Order Management', 'included' => true],
                    ['text' => 'Advanced Tracking', 'included' => true],
                    ['text' => 'Finance & Reporting', 'included' => true],
                    ['text' => 'API Access', 'included' => true],
                    ['text' => 'Priority Support', 'included' => true],
                    ['text' => 'Custom Integration', 'included' => false],
                ],
            ],
            [
                'name' => 'Enterprise',
                'description' => 'Untuk perusahaan besar',
                'price' => 10000000, // Default price untuk database, bisa diubah melalui admin panel
                'duration_days' => 30,
                'is_active' => true,
                'features' => [
                    ['text' => 'Unlimited user', 'included' => true],
                    ['text' => 'Unlimited shipment', 'included' => true],
                    ['text' => 'All Features Included', 'included' => true],
                    ['text' => 'Real-time Tracking', 'included' => true],
                    ['text' => 'Advanced Analytics', 'included' => true],
                    ['text' => 'Full API Access', 'included' => true],
                    ['text' => 'Custom Integration', 'included' => true],
                    ['text' => 'Dedicated Support', 'included' => true],
                    ['text' => 'On-premise Option', 'included' => true],
                ],
            ],
        ];

        foreach ($packages as $package) {
            Package::updateOrCreate(
                ['name' => $package['name']],
                $package
            );
        }

        $this->command->info('Packages seeded successfully!');
    }
}

