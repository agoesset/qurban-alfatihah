<?php

namespace Database\Seeders;

use App\Models\ListHewan;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ListHewanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hewans = [];
        $counter = 1;

        // Domba (30 hewan)
        for ($i = 1; $i <= 30; $i++) {
            $kategoriId = rand(1, 6); // Domba categories
            $penyembelihan = $i <= 20; // 20 sudah disembelih
            $pengulitan = $i <= 15; // 15 sudah dikuliti
            $penimbangan = $i <= 10; // 10 sudah ditimbang

            $hewans[] = [
                'kode_hewan' => 'DMB-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'kategori_id' => $kategoriId,
                'bobot' => rand(2000, 4500) / 100, // 20-45 kg
                'penyembelihan' => $penyembelihan,
                'pengulitan' => $pengulitan,
                'penimbangan' => $penimbangan,
                'penyembelihan_updated_at' => $penyembelihan ? Carbon::now()->subHours(rand(1, 24)) : null,
                'pengulitan_updated_at' => $pengulitan ? Carbon::now()->subHours(rand(1, 20)) : null,
                'penimbangan_updated_at' => $penimbangan ? Carbon::now()->subHours(rand(1, 16)) : null,
                'created_at' => Carbon::now()->subDays(rand(0, 7)),
                'updated_at' => Carbon::now()->subHours(rand(0, 48)),
            ];
        }

        // Kambing (25 hewan)
        for ($i = 1; $i <= 25; $i++) {
            $kategoriId = rand(7, 12); // Kambing categories
            $penyembelihan = $i <= 18;
            $pengulitan = $i <= 12;
            $penimbangan = $i <= 8;

            $hewans[] = [
                'kode_hewan' => 'KMB-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'kategori_id' => $kategoriId,
                'bobot' => rand(1500, 3500) / 100, // 15-35 kg
                'penyembelihan' => $penyembelihan,
                'pengulitan' => $pengulitan,
                'penimbangan' => $penimbangan,
                'penyembelihan_updated_at' => $penyembelihan ? Carbon::now()->subHours(rand(1, 24)) : null,
                'pengulitan_updated_at' => $pengulitan ? Carbon::now()->subHours(rand(1, 20)) : null,
                'penimbangan_updated_at' => $penimbangan ? Carbon::now()->subHours(rand(1, 16)) : null,
                'created_at' => Carbon::now()->subDays(rand(0, 7)),
                'updated_at' => Carbon::now()->subHours(rand(0, 48)),
            ];
        }

        // Sapi (10 hewan)
        for ($i = 1; $i <= 10; $i++) {
            $kategoriId = rand(13, 15); // Sapi categories
            $penyembelihan = $i <= 7;
            $pengulitan = $i <= 5;
            $penimbangan = $i <= 3;

            $hewans[] = [
                'kode_hewan' => 'SPI-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'kategori_id' => $kategoriId,
                'bobot' => rand(25000, 45000) / 100, // 250-450 kg
                'penyembelihan' => $penyembelihan,
                'pengulitan' => $pengulitan,
                'penimbangan' => $penimbangan,
                'penyembelihan_updated_at' => $penyembelihan ? Carbon::now()->subHours(rand(1, 24)) : null,
                'pengulitan_updated_at' => $pengulitan ? Carbon::now()->subHours(rand(1, 20)) : null,
                'penimbangan_updated_at' => $penimbangan ? Carbon::now()->subHours(rand(1, 16)) : null,
                'created_at' => Carbon::now()->subDays(rand(0, 7)),
                'updated_at' => Carbon::now()->subHours(rand(0, 48)),
            ];
        }

        // Insert all hewans
        foreach ($hewans as $hewan) {
            ListHewan::create($hewan);
        }

        $this->command->info('✅ Created 65 hewans (30 Domba, 25 Kambing, 10 Sapi)');
        $this->command->info('   - Penyembelihan: 45/65 (69%)');
        $this->command->info('   - Pengulitan: 32/65 (49%)');
        $this->command->info('   - Penimbangan: 21/65 (32%)');
    }
}
