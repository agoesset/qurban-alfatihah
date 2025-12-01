<?php

namespace App\Helpers;

use App\Models\ListDistribusi;
use App\Models\ListHewan;
use App\Models\Kategori;
use Carbon\Carbon;

class Helper
{
    // Constants untuk jenis hewan (sementara, akan diganti dengan database column)
    private const JENIS_DOMBA = 'domba';
    private const JENIS_KAMBING = 'kambing';
    private const JENIS_SAPI = 'sapi';

    /**
     * Get kategori IDs by jenis hewan based on nama_kategori pattern
     */
    private static function getKategoriIdsByJenis(string $jenis): array
    {
        $pattern = match($jenis) {
            self::JENIS_DOMBA => 'Domba%',
            self::JENIS_KAMBING => 'Kambing%',
            self::JENIS_SAPI => 'Sapi%',
            default => ''
        };

        return Kategori::where('nama_kategori', 'like', $pattern)->pluck('id')->toArray();
    }

    // ========== COUNT METHODS ==========

    public static function countDomba(): int
    {
        $kategoriIds = self::getKategoriIdsByJenis(self::JENIS_DOMBA);
        return ListHewan::whereIn('kategori_id', $kategoriIds)->count();
    }

    public static function countKambing(): int
    {
        $kategoriIds = self::getKategoriIdsByJenis(self::JENIS_KAMBING);
        return ListHewan::whereIn('kategori_id', $kategoriIds)->count();
    }

    public static function countSapi(): int
    {
        $kategoriIds = self::getKategoriIdsByJenis(self::JENIS_SAPI);
        return ListHewan::whereIn('kategori_id', $kategoriIds)->count();
    }

    // ========== PENYEMBELIHAN METHODS ==========

    public static function sembelihDomba(): int
    {
        $kategoriIds = self::getKategoriIdsByJenis(self::JENIS_DOMBA);
        return ListHewan::whereIn('kategori_id', $kategoriIds)
            ->where('penyembelihan', true)
            ->count();
    }

    public static function sembelihKambing(): int
    {
        $kategoriIds = self::getKategoriIdsByJenis(self::JENIS_KAMBING);
        return ListHewan::whereIn('kategori_id', $kategoriIds)
            ->where('penyembelihan', true)
            ->count();
    }

    public static function sembelihSapi(): int
    {
        $kategoriIds = self::getKategoriIdsByJenis(self::JENIS_SAPI);
        return ListHewan::whereIn('kategori_id', $kategoriIds)
            ->where('penyembelihan', true)
            ->count();
    }

    // ========== PENGULITAN METHODS ==========

    public static function kulitDomba(): int
    {
        $kategoriIds = self::getKategoriIdsByJenis(self::JENIS_DOMBA);
        return ListHewan::whereIn('kategori_id', $kategoriIds)
            ->where('pengulitan', true)
            ->count();
    }

    public static function kulitKambing(): int
    {
        $kategoriIds = self::getKategoriIdsByJenis(self::JENIS_KAMBING);
        return ListHewan::whereIn('kategori_id', $kategoriIds)
            ->where('pengulitan', true)
            ->count();
    }

    public static function kulitSapi(): int
    {
        $kategoriIds = self::getKategoriIdsByJenis(self::JENIS_SAPI);
        return ListHewan::whereIn('kategori_id', $kategoriIds)
            ->where('pengulitan', true)
            ->count();
    }

    // ========== PENIMBANGAN METHODS ==========

    public static function timbangDomba(): int
    {
        $kategoriIds = self::getKategoriIdsByJenis(self::JENIS_DOMBA);
        return ListHewan::whereIn('kategori_id', $kategoriIds)
            ->where('penimbangan', true)
            ->count();
    }

    public static function timbangKambing(): int
    {
        $kategoriIds = self::getKategoriIdsByJenis(self::JENIS_KAMBING);
        return ListHewan::whereIn('kategori_id', $kategoriIds)
            ->where('penimbangan', true)
            ->count();
    }

    public static function timbangSapi(): int
    {
        $kategoriIds = self::getKategoriIdsByJenis(self::JENIS_SAPI);
        return ListHewan::whereIn('kategori_id', $kategoriIds)
            ->where('penimbangan', true)
            ->count();
    }

    // ========== LAST UPDATED PENYEMBELIHAN ==========

    public static function lastUpdatedPenyembelihanDomba(): ?Carbon
    {
        $kategoriIds = self::getKategoriIdsByJenis(self::JENIS_DOMBA);
        $lastUpdated = ListHewan::whereIn('kategori_id', $kategoriIds)
            ->where('penyembelihan', true)
            ->latest('penyembelihan_updated_at')
            ->first();

        return $lastUpdated?->penyembelihan_updated_at;
    }

    public static function lastUpdatedPenyembelihanKambing(): ?Carbon
    {
        $kategoriIds = self::getKategoriIdsByJenis(self::JENIS_KAMBING);
        $lastUpdated = ListHewan::whereIn('kategori_id', $kategoriIds)
            ->where('penyembelihan', true)
            ->latest('penyembelihan_updated_at')
            ->first();

        return $lastUpdated?->penyembelihan_updated_at;
    }

    public static function lastUpdatedPenyembelihanSapi(): ?Carbon
    {
        $kategoriIds = self::getKategoriIdsByJenis(self::JENIS_SAPI);
        $lastUpdated = ListHewan::whereIn('kategori_id', $kategoriIds)
            ->where('penyembelihan', true)
            ->latest('penyembelihan_updated_at')
            ->first();

        return $lastUpdated?->penyembelihan_updated_at;
    }

    // ========== LAST UPDATED PENGULITAN ==========

    public static function lastUpdatedPengulitanDomba(): ?Carbon
    {
        $kategoriIds = self::getKategoriIdsByJenis(self::JENIS_DOMBA);
        $lastUpdated = ListHewan::whereIn('kategori_id', $kategoriIds)
            ->where('pengulitan', true)
            ->latest('pengulitan_updated_at')
            ->first();

        return $lastUpdated?->pengulitan_updated_at;
    }

    public static function lastUpdatedPengulitanKambing(): ?Carbon
    {
        $kategoriIds = self::getKategoriIdsByJenis(self::JENIS_KAMBING);
        $lastUpdated = ListHewan::whereIn('kategori_id', $kategoriIds)
            ->where('pengulitan', true)
            ->latest('pengulitan_updated_at')
            ->first();

        return $lastUpdated?->pengulitan_updated_at;
    }

    public static function lastUpdatedPengulitanSapi(): ?Carbon
    {
        $kategoriIds = self::getKategoriIdsByJenis(self::JENIS_SAPI);
        $lastUpdated = ListHewan::whereIn('kategori_id', $kategoriIds)
            ->where('pengulitan', true)
            ->latest('pengulitan_updated_at')
            ->first();

        return $lastUpdated?->pengulitan_updated_at;
    }

    // ========== LAST UPDATED PENIMBANGAN ==========

    public static function lastUpdatedPenimbanganDomba(): ?Carbon
    {
        $kategoriIds = self::getKategoriIdsByJenis(self::JENIS_DOMBA);
        $lastUpdated = ListHewan::whereIn('kategori_id', $kategoriIds)
            ->where('penimbangan', true)
            ->latest('penimbangan_updated_at')
            ->first();

        return $lastUpdated?->penimbangan_updated_at;
    }

    public static function lastUpdatedPenimbanganKambing(): ?Carbon
    {
        $kategoriIds = self::getKategoriIdsByJenis(self::JENIS_KAMBING);
        $lastUpdated = ListHewan::whereIn('kategori_id', $kategoriIds)
            ->where('penimbangan', true)
            ->latest('penimbangan_updated_at')
            ->first();

        return $lastUpdated?->penimbangan_updated_at;
    }

    public static function lastUpdatedPenimbanganSapi(): ?Carbon
    {
        $kategoriIds = self::getKategoriIdsByJenis(self::JENIS_SAPI);
        $lastUpdated = ListHewan::whereIn('kategori_id', $kategoriIds)
            ->where('penimbangan', true)
            ->latest('penimbangan_updated_at')
            ->first();

        return $lastUpdated?->penimbangan_updated_at;
    }

    // ========== DISTRIBUSI COUNT METHODS (FIXED JSON QUERIES) ==========

    public static function countDaging(): int
    {
        return ListDistribusi::where(function($query) {
            $query->whereJsonContains('request', 'Daging')
                ->orWhereJsonContains('request', 'Daging Domba')
                ->orWhereJsonContains('request', 'Daging Kambing')
                ->orWhereJsonContains('request', 'Daging Sapi');
        })->sum('jumlah') ?? 0;
    }

    public static function countJeroan(): int
    {
        return ListDistribusi::whereJsonContains('request', 'Jeroan')
            ->sum('jumlah') ?? 0;
    }

    public static function countKepalaKaki(): int
    {
        return ListDistribusi::whereJsonContains('request', 'Kepala & Kaki')
            ->sum('jumlah') ?? 0;
    }

    public static function countShohibulQurban(): int
    {
        return ListDistribusi::where('shohibul_qurban', true)->count();
    }

    public static function countPenerimaManfaat(): int
    {
        return ListDistribusi::where('shohibul_qurban', false)->count();
    }

    // ========== PEMBUNGKUSAN METHODS (FIXED JSON QUERIES) ==========

    public static function bungkusDaging(): int
    {
        return ListDistribusi::where('terbungkus', true)
            ->where(function($query) {
                $query->whereJsonContains('request', 'Daging')
                    ->orWhereJsonContains('request', 'Daging Domba')
                    ->orWhereJsonContains('request', 'Daging Kambing')
                    ->orWhereJsonContains('request', 'Daging Sapi');
            })
            ->sum('jumlah') ?? 0;
    }

    public static function bungkusJeroan(): int
    {
        return ListDistribusi::where('terbungkus', true)
            ->whereJsonContains('request', 'Jeroan')
            ->sum('jumlah') ?? 0;
    }

    public static function bungkusKepalaKaki(): int
    {
        return ListDistribusi::where('terbungkus', true)
            ->whereJsonContains('request', 'Kepala & Kaki')
            ->sum('jumlah') ?? 0;
    }

    // ========== DISTRIBUSI STATUS METHODS ==========

    public static function distribusiShohibulQurban(): int
    {
        return ListDistribusi::where('shohibul_qurban', true)
            ->where('terdistribusi', true)
            ->count();
    }

    public static function distribusiPenerimaManfaat(): int
    {
        return ListDistribusi::where('shohibul_qurban', false)
            ->where('terdistribusi', true)
            ->count();
    }

    // ========== LAST UPDATED METHODS (FIXED RETURN TYPES) ==========

    public static function lastUpdatedPembungkusan(): ?Carbon
    {
        return ListDistribusi::where('terbungkus', true)
            ->latest('updated_at')
            ->first()
            ?->updated_at;
    }

    public static function lastUpdatedDistribusiQurban(): ?Carbon
    {
        return ListDistribusi::where('terdistribusi', true)
            ->latest('updated_at')
            ->first()
            ?->updated_at;
    }

    // ========== PROGRESS CALCULATION ==========

    public static function calculateProgress(string $countMethod, string $progresMethod): array
    {
        $total = self::$countMethod();
        $progres = self::$progresMethod();
        $persentase = $total > 0 ? round(($progres / $total) * 100, 2) : 0;

        return [
            'total' => $total,
            'progres' => $progres,
            'persentase' => $persentase,
        ];
    }
}
