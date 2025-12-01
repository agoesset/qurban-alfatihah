<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\ListHewan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListHewanCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Kategori $kategori;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test user
        $this->user = User::factory()->create();

        // Create test kategori
        $this->kategori = Kategori::create([
            'nama_kategori' => 'Domba Tipe A',
            'image' => 'domba-a.jpg'
        ]);
    }

    /** @test */
    public function it_can_create_list_hewan()
    {
        $data = [
            'kode_hewan' => 'DMB-001',
            'kategori_id' => $this->kategori->id,
            'bobot' => 25.50,
            'penyembelihan' => false,
            'pengulitan' => false,
            'penimbangan' => false,
        ];

        $listHewan = ListHewan::create($data);

        $this->assertDatabaseHas('list_hewans', [
            'kode_hewan' => 'DMB-001',
            'kategori_id' => $this->kategori->id,
            'bobot' => 25.50,
        ]);

        $this->assertEquals('DMB-001', $listHewan->kode_hewan);
        $this->assertEquals(25.50, $listHewan->bobot);
    }

    /** @test */
    public function it_can_read_list_hewan()
    {
        $listHewan = ListHewan::create([
            'kode_hewan' => 'DMB-002',
            'kategori_id' => $this->kategori->id,
            'bobot' => 30.00,
            'penyembelihan' => true,
        ]);

        $found = ListHewan::find($listHewan->id);

        $this->assertNotNull($found);
        $this->assertEquals('DMB-002', $found->kode_hewan);
        $this->assertTrue($found->penyembelihan);
    }

    /** @test */
    public function it_can_update_list_hewan()
    {
        $listHewan = ListHewan::create([
            'kode_hewan' => 'DMB-003',
            'kategori_id' => $this->kategori->id,
            'bobot' => 28.00,
            'penyembelihan' => false,
        ]);

        $listHewan->update([
            'penyembelihan' => true,
            'bobot' => 29.50,
        ]);

        $this->assertDatabaseHas('list_hewans', [
            'kode_hewan' => 'DMB-003',
            'penyembelihan' => true,
            'bobot' => 29.50,
        ]);
    }

    /** @test */
    public function it_can_soft_delete_list_hewan()
    {
        $listHewan = ListHewan::create([
            'kode_hewan' => 'DMB-004',
            'kategori_id' => $this->kategori->id,
            'bobot' => 27.00,
        ]);

        $listHewan->delete();

        $this->assertSoftDeleted('list_hewans', [
            'kode_hewan' => 'DMB-004',
        ]);

        // Should not be in normal queries
        $this->assertNull(ListHewan::find($listHewan->id));

        // Should be in trashed queries
        $this->assertNotNull(ListHewan::withTrashed()->find($listHewan->id));
    }

    /** @test */
    public function it_automatically_updates_penyembelihan_timestamp()
    {
        $listHewan = ListHewan::create([
            'kode_hewan' => 'DMB-005',
            'kategori_id' => $this->kategori->id,
            'bobot' => 26.00,
            'penyembelihan' => false,
        ]);

        $this->assertNull($listHewan->penyembelihan_updated_at);

        $listHewan->update(['penyembelihan' => true]);
        $listHewan->refresh();

        $this->assertNotNull($listHewan->penyembelihan_updated_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $listHewan->penyembelihan_updated_at);
    }

    /** @test */
    public function it_has_kategori_relationship()
    {
        $listHewan = ListHewan::create([
            'kode_hewan' => 'DMB-006',
            'kategori_id' => $this->kategori->id,
            'bobot' => 24.50,
        ]);

        $this->assertInstanceOf(Kategori::class, $listHewan->kategori);
        $this->assertEquals('Domba Tipe A', $listHewan->kategori->nama_kategori);
    }

    /** @test */
    public function it_validates_unique_kode_hewan()
    {
        ListHewan::create([
            'kode_hewan' => 'DMB-007',
            'kategori_id' => $this->kategori->id,
            'bobot' => 25.00,
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        ListHewan::create([
            'kode_hewan' => 'DMB-007', // Duplicate
            'kategori_id' => $this->kategori->id,
            'bobot' => 26.00,
        ]);
    }

    /** @test */
    public function it_casts_boolean_fields_correctly()
    {
        $listHewan = ListHewan::create([
            'kode_hewan' => 'DMB-008',
            'kategori_id' => $this->kategori->id,
            'bobot' => 25.00,
            'penyembelihan' => 1,
            'pengulitan' => 0,
        ]);

        $this->assertIsBool($listHewan->penyembelihan);
        $this->assertIsBool($listHewan->pengulitan);
        $this->assertTrue($listHewan->penyembelihan);
        $this->assertFalse($listHewan->pengulitan);
    }

    /** @test */
    public function it_casts_bobot_as_decimal()
    {
        $listHewan = ListHewan::create([
            'kode_hewan' => 'DMB-009',
            'kategori_id' => $this->kategori->id,
            'bobot' => 25.5678, // Will be rounded to 2 decimals
        ]);

        $this->assertEquals('25.57', $listHewan->bobot);
    }

    /** @test */
    public function it_only_updates_timestamp_when_field_changes()
    {
        $listHewan = ListHewan::create([
            'kode_hewan' => 'DMB-010',
            'kategori_id' => $this->kategori->id,
            'bobot' => 25.00,
            'penyembelihan' => true,
        ]);

        $originalTimestamp = $listHewan->penyembelihan_updated_at;

        // Update other field
        sleep(1);
        $listHewan->update(['bobot' => 26.00]);
        $listHewan->refresh();

        // Timestamp should not change
        $this->assertEquals($originalTimestamp, $listHewan->penyembelihan_updated_at);
    }
}
