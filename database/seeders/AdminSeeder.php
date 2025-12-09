<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Skip if already exists and don't prompt in production
        $this->command->getOutput()->setVerbosity(\Symfony\Component\Console\Output\OutputInterface::VERBOSITY_QUIET);
        // Check if admin already exists
        $adminExists = User::where('email', 'admin@omile.id')->exists();

        if (!$adminExists) {
            User::create([
                'name' => 'Administrator',
                'email' => 'admin@omile.id',
                'password' => Hash::make('Admin123!'), // Default password - CHANGE THIS IN PRODUCTION!
                'role' => 'admin',
                'is_active' => true,
                'email_verified' => true,
                'email_verified_at' => now(),
                'verified_at' => now(),
            ]);

            $this->command->info('Admin user created successfully!');
            $this->command->info('Email: admin@omile.id');
            $this->command->info('Password: Admin123!');
            $this->command->warn('PLEASE CHANGE THE PASSWORD AFTER FIRST LOGIN!');
        } else {
            $this->command->info('Admin user already exists!');
        }
    }
}

