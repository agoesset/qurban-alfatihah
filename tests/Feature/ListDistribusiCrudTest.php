<?php

namespace Tests\Feature;

use App\Models\ListDistribusi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListDistribusiCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_can_create_list_distribusi()
    {
        $data = [
            'nama' => 'Budi Santoso',
            'shohibul_qurban' => true,
            'jumlah' => 5,
            'request' => ['Daging', 'Jeroan'],
            'alamat' => 'Jl. Merdeka No. 123',
            'terbungkus' => false,
            'terdistribusi' => false,
        ];

        $distribusi = ListDistribusi::create($data);

        $this->assertDatabaseHas('list_distribusis', [
            'nama' => 'Budi Santoso',
            'shohibul_qurban' => true,
            'jumlah' => 5,
            'alamat' => 'Jl. Merdeka No. 123',
        ]);

        $this->assertEquals('Budi Santoso', $distribusi->nama);
        $this->assertTrue($distribusi->shohibul_qurban);
    }

    /** @test */
    public function it_can_read_list_distribusi()
    {
        $distribusi = ListDistribusi::create([
            'nama' => 'Ani Rahmawati',
            'shohibul_qurban' => false,
            'jumlah' => 3,
            'request' => ['Daging Domba'],
            'alamat' => 'Jl. Sudirman No. 456',
        ]);

        $found = ListDistribusi::find($distribusi->id);

        $this->assertNotNull($found);
        $this->assertEquals('Ani Rahmawati', $found->nama);
        $this->assertFalse($found->shohibul_qurban);
    }

    /** @test */
    public function it_can_update_list_distribusi()
    {
        $distribusi = ListDistribusi::create([
            'nama' => 'Citra Dewi',
            'shohibul_qurban' => false,
            'jumlah' => 2,
            'request' => ['Jeroan'],
            'alamat' => 'Jl. Gatot Subroto No. 789',
            'terbungkus' => false,
        ]);

        $distribusi->update([
            'terbungkus' => true,
            'terdistribusi' => true,
        ]);

        $this->assertDatabaseHas('list_distribusis', [
            'nama' => 'Citra Dewi',
            'terbungkus' => true,
            'terdistribusi' => true,
        ]);
    }

    /** @test */
    public function it_can_delete_list_distribusi()
    {
        $distribusi = ListDistribusi::create([
            'nama' => 'Dedi Sutanto',
            'shohibul_qurban' => true,
            'jumlah' => 4,
            'request' => ['Daging'],
            'alamat' => 'Jl. Diponegoro No. 321',
        ]);

        $distribusi->delete();

        $this->assertDatabaseMissing('list_distribusis', [
            'id' => $distribusi->id,
        ]);
    }

    /** @test */
    public function it_casts_request_as_array()
    {
        $distribusi = ListDistribusi::create([
            'nama' => 'Eko Prasetyo',
            'shohibul_qurban' => false,
            'jumlah' => 6,
            'request' => ['Daging', 'Jeroan', 'Kepala & Kaki'],
            'alamat' => 'Jl. Ahmad Yani No. 654',
        ]);

        $this->assertIsArray($distribusi->request);
        $this->assertCount(3, $distribusi->request);
        $this->assertContains('Daging', $distribusi->request);
        $this->assertContains('Jeroan', $distribusi->request);
        $this->assertContains('Kepala & Kaki', $distribusi->request);
    }

    /** @test */
    public function it_casts_boolean_fields_correctly()
    {
        $distribusi = ListDistribusi::create([
            'nama' => 'Fitri Handayani',
            'shohibul_qurban' => 1,
            'jumlah' => 3,
            'request' => ['Daging'],
            'alamat' => 'Jl. Veteran No. 987',
            'terbungkus' => 0,
            'terdistribusi' => 1,
        ]);

        $this->assertIsBool($distribusi->shohibul_qurban);
        $this->assertIsBool($distribusi->terbungkus);
        $this->assertIsBool($distribusi->terdistribusi);
        $this->assertTrue($distribusi->shohibul_qurban);
        $this->assertFalse($distribusi->terbungkus);
        $this->assertTrue($distribusi->terdistribusi);
    }

    /** @test */
    public function it_casts_jumlah_as_integer()
    {
        $distribusi = ListDistribusi::create([
            'nama' => 'Gita Purnama',
            'shohibul_qurban' => false,
            'jumlah' => '7', // String input
            'request' => ['Daging'],
            'alamat' => 'Jl. Pahlawan No. 135',
        ]);

        $this->assertIsInt($distribusi->jumlah);
        $this->assertEquals(7, $distribusi->jumlah);
    }

    /** @test */
    public function it_can_query_by_request_json_contains()
    {
        ListDistribusi::create([
            'nama' => 'Hadi Susanto',
            'shohibul_qurban' => false,
            'jumlah' => 5,
            'request' => ['Daging', 'Jeroan'],
            'alamat' => 'Jl. Kartini No. 246',
        ]);

        ListDistribusi::create([
            'nama' => 'Indah Permata',
            'shohibul_qurban' => false,
            'jumlah' => 3,
            'request' => ['Kepala & Kaki'],
            'alamat' => 'Jl. Hasanuddin No. 468',
        ]);

        $dagingRecipients = ListDistribusi::whereJsonContains('request', 'Daging')->get();
        $jeroanRecipients = ListDistribusi::whereJsonContains('request', 'Jeroan')->get();
        $kepalaKakiRecipients = ListDistribusi::whereJsonContains('request', 'Kepala & Kaki')->get();

        $this->assertCount(1, $dagingRecipients);
        $this->assertCount(1, $jeroanRecipients);
        $this->assertCount(1, $kepalaKakiRecipients);
    }

    /** @test */
    public function it_can_sum_jumlah_by_request_type()
    {
        ListDistribusi::create([
            'nama' => 'Test 1',
            'shohibul_qurban' => false,
            'jumlah' => 5,
            'request' => ['Daging'],
            'alamat' => 'Address 1',
        ]);

        ListDistribusi::create([
            'nama' => 'Test 2',
            'shohibul_qurban' => false,
            'jumlah' => 3,
            'request' => ['Daging', 'Jeroan'],
            'alamat' => 'Address 2',
        ]);

        $totalDaging = ListDistribusi::whereJsonContains('request', 'Daging')->sum('jumlah');

        $this->assertEquals(8, $totalDaging);
    }

    /** @test */
    public function it_can_filter_by_shohibul_qurban_and_terdistribusi()
    {
        ListDistribusi::create([
            'nama' => 'Shohibul 1',
            'shohibul_qurban' => true,
            'jumlah' => 5,
            'request' => ['Daging'],
            'alamat' => 'Address 1',
            'terdistribusi' => true,
        ]);

        ListDistribusi::create([
            'nama' => 'Shohibul 2',
            'shohibul_qurban' => true,
            'jumlah' => 3,
            'request' => ['Daging'],
            'alamat' => 'Address 2',
            'terdistribusi' => false,
        ]);

        ListDistribusi::create([
            'nama' => 'Penerima 1',
            'shohibul_qurban' => false,
            'jumlah' => 2,
            'request' => ['Daging'],
            'alamat' => 'Address 3',
            'terdistribusi' => true,
        ]);

        $distribusiShohibul = ListDistribusi::where('shohibul_qurban', true)
            ->where('terdistribusi', true)
            ->count();

        $distribusiPenerima = ListDistribusi::where('shohibul_qurban', false)
            ->where('terdistribusi', true)
            ->count();

        $this->assertEquals(1, $distribusiShohibul);
        $this->assertEquals(1, $distribusiPenerima);
    }
}
