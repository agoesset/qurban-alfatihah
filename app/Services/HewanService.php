<?php

namespace App\Services;

use App\Exceptions\HewanNotFoundException;
use App\Exceptions\KategoriNotFoundException;
use App\Models\Kategori;
use App\Models\ListHewan;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HewanService
{
    /**
     * Create a new hewan with error handling
     */
    public function create(array $data): ListHewan
    {
        try {
            // Validate kategori exists
            if (!Kategori::find($data['kategori_id'])) {
                throw new KategoriNotFoundException($data['kategori_id']);
            }

            DB::beginTransaction();

            $hewan = ListHewan::create($data);

            DB::commit();

            Log::info('Hewan created successfully', [
                'kode_hewan' => $hewan->kode_hewan,
                'kategori_id' => $hewan->kategori_id,
            ]);

            return $hewan;
        } catch (KategoriNotFoundException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create hewan', [
                'error' => $e->getMessage(),
                'data' => $data,
            ]);
            throw new \Exception('Gagal membuat data hewan: ' . $e->getMessage());
        }
    }

    /**
     * Update hewan with error handling
     */
    public function update(int $id, array $data): ListHewan
    {
        try {
            $hewan = ListHewan::find($id);

            if (!$hewan) {
                throw new HewanNotFoundException();
            }

            // If kategori_id is being updated, validate it exists
            if (isset($data['kategori_id']) && !Kategori::find($data['kategori_id'])) {
                throw new KategoriNotFoundException($data['kategori_id']);
            }

            DB::beginTransaction();

            $hewan->update($data);

            DB::commit();

            Log::info('Hewan updated successfully', [
                'id' => $hewan->id,
                'kode_hewan' => $hewan->kode_hewan,
                'changes' => array_keys($data),
            ]);

            return $hewan->fresh();
        } catch (HewanNotFoundException | KategoriNotFoundException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update hewan', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);
            throw new \Exception('Gagal mengupdate data hewan: ' . $e->getMessage());
        }
    }

    /**
     * Update workflow status (penyembelihan, pengulitan, penimbangan)
     */
    public function updateWorkflowStatus(int $id, string $status, bool $value): ListHewan
    {
        $validStatuses = ['penyembelihan', 'pengulitan', 'penimbangan'];

        if (!in_array($status, $validStatuses)) {
            throw new \InvalidArgumentException("Status '{$status}' tidak valid. Harus salah satu dari: " . implode(', ', $validStatuses));
        }

        try {
            $hewan = ListHewan::find($id);

            if (!$hewan) {
                throw new HewanNotFoundException();
            }

            DB::beginTransaction();

            $hewan->update([$status => $value]);

            DB::commit();

            Log::info('Hewan workflow status updated', [
                'id' => $hewan->id,
                'kode_hewan' => $hewan->kode_hewan,
                'status' => $status,
                'value' => $value,
            ]);

            return $hewan->fresh();
        } catch (HewanNotFoundException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update workflow status', [
                'id' => $id,
                'status' => $status,
                'error' => $e->getMessage(),
            ]);
            throw new \Exception('Gagal mengupdate status workflow: ' . $e->getMessage());
        }
    }

    /**
     * Delete hewan (soft delete)
     */
    public function delete(int $id): bool
    {
        try {
            $hewan = ListHewan::find($id);

            if (!$hewan) {
                throw new HewanNotFoundException();
            }

            DB::beginTransaction();

            $kodeHewan = $hewan->kode_hewan;
            $deleted = $hewan->delete();

            DB::commit();

            Log::info('Hewan deleted successfully', [
                'id' => $id,
                'kode_hewan' => $kodeHewan,
            ]);

            return $deleted;
        } catch (HewanNotFoundException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete hewan', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);
            throw new \Exception('Gagal menghapus data hewan: ' . $e->getMessage());
        }
    }

    /**
     * Get hewan by kode with error handling
     */
    public function findByKode(string $kodeHewan): ListHewan
    {
        try {
            $hewan = ListHewan::where('kode_hewan', $kodeHewan)->first();

            if (!$hewan) {
                throw new HewanNotFoundException($kodeHewan);
            }

            return $hewan;
        } catch (HewanNotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Failed to find hewan by kode', [
                'kode_hewan' => $kodeHewan,
                'error' => $e->getMessage(),
            ]);
            throw new \Exception('Gagal mencari data hewan: ' . $e->getMessage());
        }
    }

    /**
     * Get all hewans by kategori with error handling
     */
    public function getByKategori(int $kategoriId): Collection
    {
        try {
            if (!Kategori::find($kategoriId)) {
                throw new KategoriNotFoundException($kategoriId);
            }

            return ListHewan::where('kategori_id', $kategoriId)->get();
        } catch (KategoriNotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Failed to get hewans by kategori', [
                'kategori_id' => $kategoriId,
                'error' => $e->getMessage(),
            ]);
            throw new \Exception('Gagal mengambil data hewan: ' . $e->getMessage());
        }
    }

    /**
     * Restore soft-deleted hewan
     */
    public function restore(int $id): ListHewan
    {
        try {
            $hewan = ListHewan::withTrashed()->find($id);

            if (!$hewan) {
                throw new HewanNotFoundException();
            }

            if (!$hewan->trashed()) {
                throw new \Exception('Hewan tidak dalam status terhapus.');
            }

            DB::beginTransaction();

            $hewan->restore();

            DB::commit();

            Log::info('Hewan restored successfully', [
                'id' => $id,
                'kode_hewan' => $hewan->kode_hewan,
            ]);

            return $hewan->fresh();
        } catch (HewanNotFoundException | \Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
