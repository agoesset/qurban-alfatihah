<?php

namespace App\Services;

use App\Exceptions\DistribusiException;
use App\Models\ListDistribusi;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DistribusiService
{
    private const VALID_REQUEST_TYPES = [
        'Daging',
        'Daging Domba',
        'Daging Kambing',
        'Daging Sapi',
        'Jeroan',
        'Kepala & Kaki',
        'Buntut',
    ];

    /**
     * Create new distribusi with validation
     */
    public function create(array $data): ListDistribusi
    {
        try {
            // Validate request types
            $this->validateRequestTypes($data['request'] ?? []);

            DB::beginTransaction();

            $distribusi = ListDistribusi::create($data);

            DB::commit();

            Log::info('Distribusi created successfully', [
                'id' => $distribusi->id,
                'nama' => $distribusi->nama,
                'shohibul_qurban' => $distribusi->shohibul_qurban,
            ]);

            return $distribusi;
        } catch (DistribusiException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create distribusi', [
                'error' => $e->getMessage(),
                'data' => $data,
            ]);
            throw new \Exception('Gagal membuat data distribusi: ' . $e->getMessage());
        }
    }

    /**
     * Update distribusi
     */
    public function update(int $id, array $data): ListDistribusi
    {
        try {
            $distribusi = ListDistribusi::find($id);

            if (!$distribusi) {
                throw new \Exception("Distribusi dengan ID {$id} tidak ditemukan.");
            }

            // Validate request types if being updated
            if (isset($data['request'])) {
                $this->validateRequestTypes($data['request']);
            }

            DB::beginTransaction();

            $distribusi->update($data);

            DB::commit();

            Log::info('Distribusi updated successfully', [
                'id' => $distribusi->id,
                'nama' => $distribusi->nama,
                'changes' => array_keys($data),
            ]);

            return $distribusi->fresh();
        } catch (DistribusiException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update distribusi', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);
            throw new \Exception('Gagal mengupdate data distribusi: ' . $e->getMessage());
        }
    }

    /**
     * Mark distribusi as packed (terbungkus)
     */
    public function markAsPacked(int $id): ListDistribusi
    {
        try {
            $distribusi = ListDistribusi::find($id);

            if (!$distribusi) {
                throw new \Exception("Distribusi dengan ID {$id} tidak ditemukan.");
            }

            DB::beginTransaction();

            $distribusi->update(['terbungkus' => true]);

            DB::commit();

            Log::info('Distribusi marked as packed', [
                'id' => $distribusi->id,
                'nama' => $distribusi->nama,
            ]);

            return $distribusi->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to mark distribusi as packed', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);
            throw new \Exception('Gagal menandai distribusi sebagai terbungkus: ' . $e->getMessage());
        }
    }

    /**
     * Mark distribusi as distributed (terdistribusi)
     */
    public function markAsDistributed(int $id, bool $requirePacked = true): ListDistribusi
    {
        try {
            $distribusi = ListDistribusi::find($id);

            if (!$distribusi) {
                throw new \Exception("Distribusi dengan ID {$id} tidak ditemukan.");
            }

            if ($distribusi->terdistribusi) {
                throw DistribusiException::alreadyDistributed($id);
            }

            if ($requirePacked && !$distribusi->terbungkus) {
                throw DistribusiException::notYetPacked($id);
            }

            DB::beginTransaction();

            $distribusi->update(['terdistribusi' => true]);

            DB::commit();

            Log::info('Distribusi marked as distributed', [
                'id' => $distribusi->id,
                'nama' => $distribusi->nama,
            ]);

            return $distribusi->fresh();
        } catch (DistribusiException | \Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Delete distribusi
     */
    public function delete(int $id): bool
    {
        try {
            $distribusi = ListDistribusi::find($id);

            if (!$distribusi) {
                throw new \Exception("Distribusi dengan ID {$id} tidak ditemukan.");
            }

            // Prevent deletion if already distributed
            if ($distribusi->terdistribusi) {
                throw new \Exception('Tidak dapat menghapus distribusi yang sudah terdistribusi.');
            }

            DB::beginTransaction();

            $nama = $distribusi->nama;
            $deleted = $distribusi->delete();

            DB::commit();

            Log::info('Distribusi deleted successfully', [
                'id' => $id,
                'nama' => $nama,
            ]);

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete distribusi', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get distribusi by shohibul qurban status
     */
    public function getByShohibulQurban(bool $isShohibul): Collection
    {
        try {
            return ListDistribusi::where('shohibul_qurban', $isShohibul)->get();
        } catch (\Exception $e) {
            Log::error('Failed to get distribusi by shohibul qurban', [
                'is_shohibul' => $isShohibul,
                'error' => $e->getMessage(),
            ]);
            throw new \Exception('Gagal mengambil data distribusi: ' . $e->getMessage());
        }
    }

    /**
     * Validate request types
     */
    private function validateRequestTypes(array $requestTypes): void
    {
        foreach ($requestTypes as $type) {
            if (!in_array($type, self::VALID_REQUEST_TYPES)) {
                throw DistribusiException::invalidRequestType($type);
            }
        }
    }
}
