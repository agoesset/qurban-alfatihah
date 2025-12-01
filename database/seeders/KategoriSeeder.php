<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoris = [
            // Domba (ID 1-6)
            ['id' => 1, 'nama_kategori' => 'Domba Promo', 'image' => 'images/kategoris/domba-promo.jpg'],
            ['id' => 2, 'nama_kategori' => 'Domba Tipe A', 'image' => 'images/kategoris/domba-a.jpg'],
            ['id' => 3, 'nama_kategori' => 'Domba Tipe B', 'image' => 'images/kategoris/domba-b.jpg'],
            ['id' => 4, 'nama_kategori' => 'Domba Tipe C', 'image' => 'images/kategoris/domba-c.jpg'],
            ['id' => 5, 'nama_kategori' => 'Domba Tipe D', 'image' => 'images/kategoris/domba-d.jpg'],
            ['id' => 6, 'nama_kategori' => 'Domba Spesial', 'image' => 'images/kategoris/domba-spesial.jpg'],

            // Kambing (ID 7-12)
            ['id' => 7, 'nama_kategori' => 'Kambing Promo', 'image' => 'images/kategoris/kambing-promo.jpg'],
            ['id' => 8, 'nama_kategori' => 'Kambing Tipe A', 'image' => 'images/kategoris/kambing-a.jpg'],
            ['id' => 9, 'nama_kategori' => 'Kambing Tipe B', 'image' => 'images/kategoris/kambing-b.jpg'],
            ['id' => 10, 'nama_kategori' => 'Kambing Tipe C', 'image' => 'images/kategoris/kambing-c.jpg'],
            ['id' => 11, 'nama_kategori' => 'Kambing Tipe D', 'image' => 'images/kategoris/kambing-d.jpg'],
            ['id' => 12, 'nama_kategori' => 'Kambing Tipe E', 'image' => 'images/kategoris/kambing-e.jpg'],

            // Sapi (ID 13-15)
            ['id' => 13, 'nama_kategori' => 'Sapi Jawa Favorit', 'image' => 'images/kategoris/sapi-favorit.jpg'],
            ['id' => 14, 'nama_kategori' => 'Sapi Jawa Premium', 'image' => 'images/kategoris/sapi-premium.jpg'],
            ['id' => 15, 'nama_kategori' => 'Sapi Jawa Super', 'image' => 'images/kategoris/sapi-super.jpg'],
        ];

        foreach ($kategoris as $kategori) {
            Kategori::create($kategori);
        }

        $this->command->info('✅ Created 15 kategoris (6 Domba, 6 Kambing, 3 Sapi)');
    }
}
