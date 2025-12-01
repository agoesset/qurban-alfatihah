<?php

namespace Tests\Unit;

use App\Helpers\Helper;
use App\Models\Kategori;
use App\Models\ListHewan;
use App\Models\ListDistribusi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class HelperTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test data
        $this->createTestKategoris();
        $this->createTestHewans();
        $this->createTestDistribusis();
    }

    private function createTestKategoris(): void
    {
        Kategori::create(['id' => 1, 'nama_kategori' => 'Domba Tipe A', 'image' => 'domba-a.jpg']);
        Kategori::create(['id' => 2, 'nama_kategori' => 'Domba Tipe B', 'image' => 'domba-b.jpg']);
        Kategori::create(['id' => 3, 'nama_kategori' => 'Kambing Tipe A', 'image' => 'kambing-a.jpg']);
        Kategori::create(['id' => 4, 'nama_kategori' => 'Kambing Tipe B', 'image' => 'kambing-b.jpg']);
        Kategori::create(['id' => 5, 'nama_kategori' => 'Sapi Jawa Premium', 'image' => 'sapi-premium.jpg']);
    }

    private function createTestHewans(): void
    {
        // Domba
        ListHewan::create(['kode_hewan' => 'DMB-001', 'kategori_id' => 1, 'bobot' => 25.50, 'penyembelihan' => false]);
        ListHewan::create(['kode_hewan' => 'DMB-002', 'kategori_id' => 2, 'bobot' => 30.00, 'penyembelihan' => true, 'penyembelihan_updated_at' => Carbon::now()]);

        // Kambing
        ListHewan::create(['kode_hewan' => 'KMB-001', 'kategori_id' => 3, 'bobot' => 20.00, 'penyembelihan' => false]);
        ListHewan::create(['kode_hewan' => 'KMB-002', 'kategori_id' => 4, 'bobot' => 22.50, 'penyembelihan' => true, 'pengulitan' => true, 'penyembelihan_updated_at' => Carbon::now(), 'pengulitan_updated_at' => Carbon::now()]);

        // Sapi
        ListHewan::create(['kode_hewan' => 'SPI-001', 'kategori_id' => 5, 'bobot' => 350.00, 'penyembelihan' => true, 'pengulitan' => true, 'penimbangan' => true, 'penyembelihan_updated_at' => Carbon::now(), 'pengulitan_updated_at' => Carbon::now(), 'penimbangan_updated_at' => Carbon::now()]);
    }

    private function createTestDistribusis(): void
    {
        ListDistribusi::create([
            'nama' => 'Budi Santoso',
            'shohibul_qurban' => true,
            'jumlah' => 5,
            'request' => ['Daging', 'Jeroan'],
            'alamat' => 'Jl. Merdeka No. 123',
            'terbungkus' => true,
            'terdistribusi' => true
        ]);

        ListDistribusi::create([
            'nama' => 'Ani Rahmawati',
            'shohibul_qurban' => false,
            'jumlah' => 3,
            'request' => ['Daging Domba', 'Kepala & Kaki'],
            'alamat' => 'Jl. Sudirman No. 456',
            'terbungkus' => true,
            'terdistribusi' => false
        ]);

        ListDistribusi::create([
            'nama' => 'Citra Dewi',
            'shohibul_qurban' => false,
            'jumlah' => 2,
            'request' => ['Jeroan'],
            'alamat' => 'Jl. Gatot Subroto No. 789',
            'terbungkus' => false,
            'terdistribusi' => false
        ]);
    }

    /** @test */
    public function it_counts_domba_correctly()
    {
        $count = Helper::countDomba();
        $this->assertEquals(2, $count);
    }

    /** @test */
    public function it_counts_kambing_correctly()
    {
        $count = Helper::countKambing();
        $this->assertEquals(2, $count);
    }

    /** @test */
    public function it_counts_sapi_correctly()
    {
        $count = Helper::countSapi();
        $this->assertEquals(1, $count);
    }

    /** @test */
    public function it_counts_sembelih_domba_correctly()
    {
        $count = Helper::sembelihDomba();
        $this->assertEquals(1, $count);
    }

    /** @test */
    public function it_counts_sembelih_kambing_correctly()
    {
        $count = Helper::sembelihKambing();
        $this->assertEquals(1, $count);
    }

    /** @test */
    public function it_counts_sembelih_sapi_correctly()
    {
        $count = Helper::sembelihSapi();
        $this->assertEquals(1, $count);
    }

    /** @test */
    public function it_counts_kulit_kambing_correctly()
    {
        $count = Helper::kulitKambing();
        $this->assertEquals(1, $count);
    }

    /** @test */
    public function it_counts_timbang_sapi_correctly()
    {
        $count = Helper::timbangSapi();
        $this->assertEquals(1, $count);
    }

    /** @test */
    public function it_returns_last_updated_penyembelihan_domba()
    {
        $lastUpdated = Helper::lastUpdatedPenyembelihanDomba();
        $this->assertInstanceOf(Carbon::class, $lastUpdated);
    }

    /** @test */
    public function it_returns_null_when_no_penyembelihan_domba()
    {
        // Delete all domba penyembelihan
        ListHewan::whereIn('kategori_id', [1, 2])->update(['penyembelihan' => false]);

        $lastUpdated = Helper::lastUpdatedPenyembelihanDomba();
        $this->assertNull($lastUpdated);
    }

    /** @test */
    public function it_counts_daging_correctly()
    {
        $count = Helper::countDaging();
        $this->assertEquals(8, $count); // 5 + 3 = 8
    }

    /** @test */
    public function it_counts_jeroan_correctly()
    {
        $count = Helper::countJeroan();
        $this->assertEquals(7, $count); // 5 + 2 = 7
    }

    /** @test */
    public function it_counts_kepala_kaki_correctly()
    {
        $count = Helper::countKepalaKaki();
        $this->assertEquals(3, $count);
    }

    /** @test */
    public function it_counts_shohibul_qurban_correctly()
    {
        $count = Helper::countShohibulQurban();
        $this->assertEquals(1, $count);
    }

    /** @test */
    public function it_counts_penerima_manfaat_correctly()
    {
        $count = Helper::countPenerimaManfaat();
        $this->assertEquals(2, $count);
    }

    /** @test */
    public function it_counts_bungkus_daging_correctly()
    {
        $count = Helper::bungkusDaging();
        $this->assertEquals(8, $count); // Only terbungkus=true (5 + 3)
    }

    /** @test */
    public function it_counts_bungkus_jeroan_correctly()
    {
        $count = Helper::bungkusJeroan();
        $this->assertEquals(5, $count); // Only first record (terbungkus=true)
    }

    /** @test */
    public function it_counts_distribusi_shohibul_qurban_correctly()
    {
        $count = Helper::distribusiShohibulQurban();
        $this->assertEquals(1, $count);
    }

    /** @test */
    public function it_counts_distribusi_penerima_manfaat_correctly()
    {
        $count = Helper::distribusiPenerimaManfaat();
        $this->assertEquals(0, $count); // No penerima manfaat with terdistribusi=true
    }

    /** @test */
    public function it_returns_last_updated_pembungkusan()
    {
        $lastUpdated = Helper::lastUpdatedPembungkusan();
        $this->assertInstanceOf(Carbon::class, $lastUpdated);
    }

    /** @test */
    public function it_returns_last_updated_distribusi_qurban()
    {
        $lastUpdated = Helper::lastUpdatedDistribusiQurban();
        $this->assertInstanceOf(Carbon::class, $lastUpdated);
    }

    /** @test */
    public function it_calculates_progress_correctly()
    {
        $progress = Helper::calculateProgress('countDomba', 'sembelihDomba');

        $this->assertIsArray($progress);
        $this->assertArrayHasKey('total', $progress);
        $this->assertArrayHasKey('progres', $progress);
        $this->assertArrayHasKey('persentase', $progress);

        $this->assertEquals(2, $progress['total']);
        $this->assertEquals(1, $progress['progres']);
        $this->assertEquals(50.0, $progress['persentase']);
    }

    /** @test */
    public function it_calculates_progress_with_zero_total()
    {
        // Delete all domba
        ListHewan::whereIn('kategori_id', [1, 2])->delete();

        $progress = Helper::calculateProgress('countDomba', 'sembelihDomba');

        $this->assertEquals(0, $progress['total']);
        $this->assertEquals(0, $progress['progres']);
        $this->assertEquals(0, $progress['persentase']);
    }

    /** @test */
    public function it_handles_multiple_request_types_in_json()
    {
        // Test that JSON contains queries work with multiple values
        ListDistribusi::create([
            'nama' => 'Test Multiple',
            'shohibul_qurban' => false,
            'jumlah' => 10,
            'request' => ['Daging', 'Jeroan', 'Kepala & Kaki', 'Buntut'],
            'alamat' => 'Test Address',
            'terbungkus' => true,
            'terdistribusi' => true
        ]);

        $dagingCount = Helper::countDaging();
        $jeroanCount = Helper::countJeroan();
        $kepalaKakiCount = Helper::countKepalaKaki();

        $this->assertEquals(18, $dagingCount); // 8 + 10
        $this->assertEquals(17, $jeroanCount); // 7 + 10
        $this->assertEquals(13, $kepalaKakiCount); // 3 + 10
    }
}
