<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\ListHewan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KategoriCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_can_create_kategori()
    {
        $data = [
            'nama_kategori' => 'Domba Tipe A',
            'image' => 'domba-a.jpg',
        ];

        $kategori = Kategori::create($data);

        $this->assertDatabaseHas('kategoris', [
            'nama_kategori' => 'Domba Tipe A',
            'image' => 'domba-a.jpg',
        ]);

        $this->assertEquals('Domba Tipe A', $kategori->nama_kategori);
    }

    /** @test */
    public function it_can_read_kategori()
    {
        $kategori = Kategori::create([
            'nama_kategori' => 'Kambing Tipe B',
            'image' => 'kambing-b.jpg',
        ]);

        $found = Kategori::find($kategori->id);

        $this->assertNotNull($found);
        $this->assertEquals('Kambing Tipe B', $found->nama_kategori);
    }

    /** @test */
    public function it_can_update_kategori()
    {
        $kategori = Kategori::create([
            'nama_kategori' => 'Sapi Jawa Premium',
            'image' => 'sapi-premium.jpg',
        ]);

        $kategori->update([
            'nama_kategori' => 'Sapi Jawa Super',
            'image' => 'sapi-super.jpg',
        ]);

        $this->assertDatabaseHas('kategoris', [
            'nama_kategori' => 'Sapi Jawa Super',
            'image' => 'sapi-super.jpg',
        ]);
    }

    /** @test */
    public function it_can_delete_kategori()
    {
        $kategori = Kategori::create([
            'nama_kategori' => 'Domba Promo',
            'image' => 'domba-promo.jpg',
        ]);

        $kategori->delete();

        $this->assertDatabaseMissing('kategoris', [
            'id' => $kategori->id,
        ]);
    }

    /** @test */
    public function it_has_list_hewans_relationship()
    {
        $kategori = Kategori::create([
            'nama_kategori' => 'Domba Tipe C',
            'image' => 'domba-c.jpg',
        ]);

        ListHewan::create([
            'kode_hewan' => 'DMB-001',
            'kategori_id' => $kategori->id,
            'bobot' => 25.50,
        ]);

        ListHewan::create([
            'kode_hewan' => 'DMB-002',
            'kategori_id' => $kategori->id,
            'bobot' => 30.00,
        ]);

        $this->assertCount(2, $kategori->listHewans);
        $this->assertInstanceOf(ListHewan::class, $kategori->listHewans->first());
    }

    /** @test */
    public function it_cascades_delete_to_list_hewans()
    {
        $kategori = Kategori::create([
            'nama_kategori' => 'Kambing Tipe D',
            'image' => 'kambing-d.jpg',
        ]);

        $listHewan = ListHewan::create([
            'kode_hewan' => 'KMB-001',
            'kategori_id' => $kategori->id,
            'bobot' => 22.50,
        ]);

        $kategori->delete();

        // Check if ListHewan was also deleted due to cascadeOnDelete
        $this->assertDatabaseMissing('list_hewans', [
            'id' => $listHewan->id,
        ]);
    }

    /** @test */
    public function it_can_query_by_nama_kategori_pattern()
    {
        Kategori::create(['nama_kategori' => 'Domba Tipe A', 'image' => 'domba-a.jpg']);
        Kategori::create(['nama_kategori' => 'Domba Tipe B', 'image' => 'domba-b.jpg']);
        Kategori::create(['nama_kategori' => 'Kambing Tipe A', 'image' => 'kambing-a.jpg']);
        Kategori::create(['nama_kategori' => 'Sapi Jawa Premium', 'image' => 'sapi-premium.jpg']);

        $dombaKategoris = Kategori::where('nama_kategori', 'like', 'Domba%')->get();
        $kambingKategoris = Kategori::where('nama_kategori', 'like', 'Kambing%')->get();
        $sapiKategoris = Kategori::where('nama_kategori', 'like', 'Sapi%')->get();

        $this->assertCount(2, $dombaKategoris);
        $this->assertCount(1, $kambingKategoris);
        $this->assertCount(1, $sapiKategoris);
    }

    /** @test */
    public function it_validates_unique_nama_kategori()
    {
        Kategori::create([
            'nama_kategori' => 'Domba Spesial',
            'image' => 'domba-spesial.jpg',
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Kategori::create([
            'nama_kategori' => 'Domba Spesial', // Duplicate
            'image' => 'domba-spesial-2.jpg',
        ]);
    }

    /** @test */
    public function it_accepts_null_image()
    {
        $kategori = Kategori::create([
            'nama_kategori' => 'Kambing Tipe E',
            'image' => null,
        ]);

        $this->assertNull($kategori->image);
        $this->assertDatabaseHas('kategoris', [
            'nama_kategori' => 'Kambing Tipe E',
            'image' => null,
        ]);
    }
}
