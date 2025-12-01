<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Check environment
        if (app()->environment('local', 'development')) {
            // For development: use DevelopmentSeeder with sample data
            $this->call(DevelopmentSeeder::class);
        } else {
            // For production: only seed kategoris
            $this->call(KategoriSeeder::class);
            $this->command->info('✅ Production seeding completed (Kategoris only)');
            $this->command->warn('⚠️  Create admin user manually for security');
        }
    }
}
