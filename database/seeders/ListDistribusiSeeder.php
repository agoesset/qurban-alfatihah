<?php

namespace Database\Seeders;

use App\Models\ListDistribusi;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ListDistribusiSeeder extends Seeder
{
    private array $namaList = [
        'Ahmad Yusuf', 'Siti Aminah', 'Budi Santoso', 'Dewi Lestari', 'Eko Prasetyo',
        'Fitri Handayani', 'Gunawan Wijaya', 'Hani Kartika', 'Indra Kusuma', 'Joko Widodo',
        'Kartini Sari', 'Lukman Hakim', 'Maya Sari', 'Nur Hidayat', 'Oktavia Putri',
        'Putra Mahendra', 'Qomariyah Dewi', 'Rizki Ramadhan', 'Sari Dewi', 'Taufik Rahman',
        'Umar Faruq', 'Vita Anggraini', 'Wahyu Nugroho', 'Yanti Susanti', 'Zainal Abidin',
    ];

    private array $alamatList = [
        'Jl. Merdeka No. 123, Jakarta', 'Jl. Sudirman No. 456, Bandung',
        'Jl. Gatot Subroto No. 789, Surabaya', 'Jl. Ahmad Yani No. 321, Semarang',
        'Jl. Diponegoro No. 654, Yogyakarta', 'Jl. Veteran No. 987, Malang',
        'Jl. Pahlawan No. 135, Solo', 'Jl. Kartini No. 246, Medan',
        'Jl. Hasanuddin No. 468, Makassar', 'Jl. Cendrawasih No. 579, Denpasar',
    ];

    private array $requestOptions = [
        ['Daging'],
        ['Daging', 'Jeroan'],
        ['Daging', 'Kepala & Kaki'],
        ['Jeroan'],
        ['Kepala & Kaki'],
        ['Daging', 'Jeroan', 'Kepala & Kaki'],
        ['Daging Domba'],
        ['Daging Kambing'],
        ['Daging Sapi'],
        ['Daging', 'Buntut'],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $distribusis = [];

        // Shohibul Qurban (15 orang) - Pemilik hewan kurban
        for ($i = 0; $i < 15; $i++) {
            $terbungkus = $i < 12; // 12 sudah terbungkus
            $terdistribusi = $i < 10; // 10 sudah terdistribusi

            $distribusis[] = [
                'nama' => $this->namaList[$i],
                'shohibul_qurban' => true,
                'jumlah' => rand(3, 10),
                'request' => $this->requestOptions[array_rand($this->requestOptions)],
                'alamat' => $this->alamatList[array_rand($this->alamatList)],
                'terbungkus' => $terbungkus,
                'terdistribusi' => $terdistribusi,
                'created_at' => Carbon::now()->subDays(rand(0, 5)),
                'updated_at' => Carbon::now()->subHours(rand(0, 48)),
            ];
        }

        // Penerima Manfaat (40 orang) - Penerima sedekah
        for ($i = 15; $i < 55; $i++) {
            $namaIndex = $i % count($this->namaList);
            $terbungkus = $i < 40; // 25 sudah terbungkus
            $terdistribusi = $i < 35; // 20 sudah terdistribusi

            $distribusis[] = [
                'nama' => $this->namaList[$namaIndex] . ' ' . chr(65 + ($i / count($this->namaList))),
                'shohibul_qurban' => false,
                'jumlah' => rand(1, 5),
                'request' => $this->requestOptions[array_rand($this->requestOptions)],
                'alamat' => $this->alamatList[array_rand($this->alamatList)],
                'terbungkus' => $terbungkus,
                'terdistribusi' => $terdistribusi,
                'created_at' => Carbon::now()->subDays(rand(0, 5)),
                'updated_at' => Carbon::now()->subHours(rand(0, 48)),
            ];
        }

        // Insert all distribusis
        foreach ($distribusis as $distribusi) {
            ListDistribusi::create($distribusi);
        }

        $this->command->info('✅ Created 55 distribusi records');
        $this->command->info('   - Shohibul Qurban: 15');
        $this->command->info('   - Penerima Manfaat: 40');
        $this->command->info('   - Terbungkus: 37/55 (67%)');
        $this->command->info('   - Terdistribusi: 30/55 (55%)');
    }
}
