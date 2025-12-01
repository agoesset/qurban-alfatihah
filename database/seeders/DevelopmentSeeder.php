<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DevelopmentSeeder extends Seeder
{
    /**
     * Run the database seeds for development environment
     */
    public function run(): void
    {
        $this->command->info('🌱 Seeding development data...');

        // Create admin user
        $admin = User::create([
            'name' => 'Admin Qurban',
            'email' => 'admin@qurban.test',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $this->command->info('✅ Created admin user (admin@qurban.test / password)');

        // Create test users
        User::create([
            'name' => 'Staff Qurban',
            'email' => 'staff@qurban.test',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'User Test',
            'email' => 'user@qurban.test',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $this->command->info('✅ Created 2 additional test users');

        // Seed kategori, hewans, and distribusis
        $this->call([
            KategoriSeeder::class,
            ListHewanSeeder::class,
            ListDistribusiSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('🎉 Development seeding completed!');
        $this->command->info('');
        $this->command->info('Login credentials:');
        $this->command->info('  Email: admin@qurban.test');
        $this->command->info('  Password: password');
    }
}
