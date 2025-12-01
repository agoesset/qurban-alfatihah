<?php

namespace App\Helpers;

use App\Models\ListDistribusi;
use App\Models\ListHewan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

/**
 * Refactored Helper class with reduced code repetition
 * Uses query scopes, caching, and dynamic methods
 */
class HelperRefactored
{
    private const CACHE_TTL = 300; // 5 minutes
    private const JENIS_TYPES = ['domba', 'kambing', 'sapi'];
    private const WORKFLOW_TYPES = ['penyembelihan', 'pengulitan', 'penimbangan'];

    // ========== DYNAMIC COUNT METHODS ==========

    /**
     * Get count of hewans by jenis
     */
    public static function countByJenis(string $jenis): int
    {
        return Cache::remember("hewan_count_{$jenis}", self::CACHE_TTL, function() use ($jenis) {
            return ListHewan::ofJenis($jenis)->count();
        });
    }

    /**
     * Get count of hewans by workflow status and jenis
     */
    public static function countByWorkflow(string $jenis, string $workflow): int
    {
        return Cache::remember("hewan_{$workflow}_{$jenis}", self::CACHE_TTL, function() use ($jenis, $workflow) {
            return ListHewan::ofJenis($jenis)->{$workflow}(true)->count();
        });
    }

    /**
     * Get last updated timestamp for workflow and jenis
     */
    public static function lastUpdatedWorkflow(string $jenis, string $workflow): ?Carbon
    {
        $cacheKey = "last_updated_{$workflow}_{$jenis}";

        return Cache::remember($cacheKey, self::CACHE_TTL, function() use ($jenis, $workflow) {
            $record = ListHewan::ofJenis($jenis)
                ->{$workflow}(true)
                ->latest("{$workflow}_updated_at")
                ->first();

            return $record?->{"{$workflow}_updated_at"};
        });
    }

    // ========== CONVENIENT WRAPPER METHODS ==========

    // Count methods
    public static function countDomba(): int { return self::countByJenis('domba'); }
    public static function countKambing(): int { return self::countByJenis('kambing'); }
    public static function countSapi(): int { return self::countByJenis('sapi'); }

    // Penyembelihan methods
    public static function sembelihDomba(): int { return self::countByWorkflow('domba', 'disembelih'); }
    public static function sembelihKambing(): int { return self::countByWorkflow('kambing', 'disembelih'); }
    public static function sembelihSapi(): int { return self::countByWorkflow('sapi', 'disembelih'); }

    // Pengulitan methods
    public static function kulitDomba(): int { return self::countByWorkflow('domba', 'dikuliti'); }
    public static function kulitKambing(): int { return self::countByWorkflow('kambing', 'dikuliti'); }
    public static function kulitSapi(): int { return self::countByWorkflow('sapi', 'dikuliti'); }

    // Penimbangan methods
    public static function timbangDomba(): int { return self::countByWorkflow('domba', 'ditimbang'); }
    public static function timbangKambing(): int { return self::countByWorkflow('kambing', 'ditimbang'); }
    public static function timbangSapi(): int { return self::countByWorkflow('sapi', 'ditimbang'); }

    // Last updated methods
    public static function lastUpdatedPenyembelihanDomba(): ?Carbon { return self::lastUpdatedWorkflow('domba', 'penyembelihan'); }
    public static function lastUpdatedPenyembelihanKambing(): ?Carbon { return self::lastUpdatedWorkflow('kambing', 'penyembelihan'); }
    public static function lastUpdatedPenyembelihanSapi(): ?Carbon { return self::lastUpdatedWorkflow('sapi', 'penyembelihan'); }

    public static function lastUpdatedPengulitanDomba(): ?Carbon { return self::lastUpdatedWorkflow('domba', 'pengulitan'); }
    public static function lastUpdatedPengulitanKambing(): ?Carbon { return self::lastUpdatedWorkflow('kambing', 'pengulitan'); }
    public static function lastUpdatedPengulitanSapi(): ?Carbon { return self::lastUpdatedWorkflow('sapi', 'pengulitan'); }

    public static function lastUpdatedPenimbanganDomba(): ?Carbon { return self::lastUpdatedWorkflow('domba', 'penimbangan'); }
    public static function lastUpdatedPenimbanganKambing(): ?Carbon { return self::lastUpdatedWorkflow('kambing', 'penimbangan'); }
    public static function lastUpdatedPenimbanganSapi(): ?Carbon { return self::lastUpdatedWorkflow('sapi', 'penimbangan'); }

    // ========== DISTRIBUTION METHODS WITH CACHING ==========

    /**
     * Count distribusi by request type (daging, jeroan, etc)
     */
    private static function countByRequestType(array $types): int
    {
        $cacheKey = 'distribusi_' . md5(implode('_', $types));

        return Cache::remember($cacheKey, self::CACHE_TTL, function() use ($types) {
            return ListDistribusi::where(function($query) use ($types) {
                foreach ($types as $type) {
                    $query->orWhereJsonContains('request', $type);
                }
            })->sum('jumlah') ?? 0;
        });
    }

    public static function countDaging(): int
    {
        return self::countByRequestType(['Daging', 'Daging Domba', 'Daging Kambing', 'Daging Sapi']);
    }

    public static function countJeroan(): int
    {
        return self::countByRequestType(['Jeroan']);
    }

    public static function countKepalaKaki(): int
    {
        return self::countByRequestType(['Kepala & Kaki']);
    }

    public static function countBuntut(): int
    {
        return self::countByRequestType(['Buntut']);
    }

    /**
     * Count shohibul qurban and penerima manfaat
     */
    public static function countShohibulQurban(): int
    {
        return Cache::remember('count_shohibul_qurban', self::CACHE_TTL, function() {
            return ListDistribusi::where('shohibul_qurban', true)->count();
        });
    }

    public static function countPenerimaManfaat(): int
    {
        return Cache::remember('count_penerima_manfaat', self::CACHE_TTL, function() {
            return ListDistribusi::where('shohibul_qurban', false)->count();
        });
    }

    /**
     * Count packed (terbungkus) by request type
     */
    private static function countPackedByRequestType(array $types): int
    {
        $cacheKey = 'packed_' . md5(implode('_', $types));

        return Cache::remember($cacheKey, self::CACHE_TTL, function() use ($types) {
            return ListDistribusi::where('terbungkus', true)
                ->where(function($query) use ($types) {
                    foreach ($types as $type) {
                        $query->orWhereJsonContains('request', $type);
                    }
                })
                ->sum('jumlah') ?? 0;
        });
    }

    public static function bungkusDaging(): int
    {
        return self::countPackedByRequestType(['Daging', 'Daging Domba', 'Daging Kambing', 'Daging Sapi']);
    }

    public static function bungkusJeroan(): int
    {
        return self::countPackedByRequestType(['Jeroan']);
    }

    public static function bungkusKepalaKaki(): int
    {
        return self::countPackedByRequestType(['Kepala & Kaki']);
    }

    /**
     * Count distributed items
     */
    public static function distribusiShohibulQurban(): int
    {
        return Cache::remember('distribusi_shohibul', self::CACHE_TTL, function() {
            return ListDistribusi::where('shohibul_qurban', true)
                ->where('terdistribusi', true)
                ->count();
        });
    }

    public static function distribusiPenerimaManfaat(): int
    {
        return Cache::remember('distribusi_penerima', self::CACHE_TTL, function() {
            return ListDistribusi::where('shohibul_qurban', false)
                ->where('terdistribusi', true)
                ->count();
        });
    }

    /**
     * Last updated timestamps for distribusi
     */
    public static function lastUpdatedPembungkusan(): ?Carbon
    {
        return Cache::remember('last_pembungkusan', self::CACHE_TTL, function() {
            return ListDistribusi::where('terbungkus', true)
                ->latest('updated_at')
                ->first()
                ?->updated_at;
        });
    }

    public static function lastUpdatedDistribusiQurban(): ?Carbon
    {
        return Cache::remember('last_distribusi', self::CACHE_TTL, function() {
            return ListDistribusi::where('terdistribusi', true)
                ->latest('updated_at')
                ->first()
                ?->updated_at;
        });
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

    // ========== CACHE MANAGEMENT ==========

    /**
     * Clear all cached statistics
     */
    public static function clearCache(): void
    {
        // Clear specific cache keys
        foreach (self::JENIS_TYPES as $jenis) {
            Cache::forget("hewan_count_{$jenis}");

            foreach (self::WORKFLOW_TYPES as $workflow) {
                Cache::forget("hewan_{$workflow}_{$jenis}");
                Cache::forget("last_updated_{$workflow}_{$jenis}");
            }
        }

        // Clear distribusi caches
        $distribusiKeys = [
            'count_shohibul_qurban',
            'count_penerima_manfaat',
            'distribusi_shohibul',
            'distribusi_penerima',
            'last_pembungkusan',
            'last_distribusi',
        ];

        foreach ($distribusiKeys as $key) {
            Cache::forget($key);
        }
    }

    /**
     * Warm up cache by pre-loading all statistics
     */
    public static function warmUpCache(): void
    {
        // Warm up hewan counts
        foreach (self::JENIS_TYPES as $jenis) {
            self::countByJenis($jenis);

            foreach (['disembelih', 'dikuliti', 'ditimbang'] as $workflow) {
                self::countByWorkflow($jenis, $workflow);
            }
        }

        // Warm up distribusi counts
        self::countDaging();
        self::countJeroan();
        self::countKepalaKaki();
        self::countShohibulQurban();
        self::countPenerimaManfaat();
    }
}
