<?php

use App\Models\ListDistribusi;
use App\Models\ListHewan;
use Carbon\Carbon;

class Helper
{
    public static function countDomba()
    {
        return ListHewan::whereBetween('kategori_id', [1, 6])->count();
    }

    public static function countKambing()
    {
        return ListHewan::whereBetween('kategori_id', [7, 12])->count();
    }

    public static function countSapi()
    {
        return ListHewan::whereBetween('kategori_id', [13, 15])->count();
    }

    public static function sembelihDomba()
    {
        return ListHewan::whereBetween('kategori_id', [1, 6])->where('penyembelihan', true)->count();
    }

    public static function sembelihKambing()
    {
        return ListHewan::whereBetween('kategori_id', [7, 12])->where('penyembelihan', true)->count();
    }

    public static function sembelihSapi()
    {
        return ListHewan::whereBetween('kategori_id', [13, 15])->where('penyembelihan', true)->count();
    }

    public static function kulitDomba()
    {
        return ListHewan::whereBetween('kategori_id', [1, 6])->where('pengulitan', true)->count();
    }

    public static function kulitKambing()
    {
        return ListHewan::whereBetween('kategori_id', [7, 12])->where('pengulitan', true)->count();
    }

    public static function kulitSapi()
    {
        return ListHewan::whereBetween('kategori_id', [13, 15])->where('pengulitan', true)->count();
    }

    public static function timbangDomba()
    {
        return ListHewan::whereBetween('kategori_id', [1, 6])->where('penimbangan', true)->count();
    }

    public static function timbangKambing()
    {
        return ListHewan::whereBetween('kategori_id', [7, 12])->where('penimbangan', true)->count();
    }

    public static function timbangSapi()
    {
        return ListHewan::whereBetween('kategori_id', [13, 15])->where('penimbangan', true)->count();
    }

    public static function lastUpdatedPenyembelihanDomba()
    {
        $lastUpdated = ListHewan::whereBetween('kategori_id', [1, 6])
            ->where('penyembelihan', true)
            ->latest('penyembelihan_updated_at')
            ->first();
        return $lastUpdated ? $lastUpdated->penyembelihan_updated_at : 'Tidak ada data';
    }

    public static function lastUpdatedPenyembelihanKambing()
    {
        $lastUpdated = ListHewan::whereBetween('kategori_id', [7, 12])
            ->where('penyembelihan', true)
            ->latest('penyembelihan_updated_at')
            ->first();
        return $lastUpdated ? $lastUpdated->penyembelihan_updated_at : 'Tidak ada data';
    }

    public static function lastUpdatedPenyembelihanSapi()
    {
        $lastUpdated = ListHewan::whereBetween('kategori_id', [13, 15])
            ->where('penyembelihan', true)
            ->latest('penyembelihan_updated_at')
            ->first();
        return $lastUpdated ? $lastUpdated->penyembelihan_updated_at : 'Tidak ada data';
    }

    public static function lastUpdatedPengulitanDomba()
    {
        $lastUpdated = ListHewan::whereBetween('kategori_id', [1, 6])
            ->where('pengulitan', true)
            ->latest('pengulitan_updated_at')
            ->first();
        return $lastUpdated ? $lastUpdated->pengulitan_updated_at : 'Tidak ada data';
    }

    public static function lastUpdatedPengulitanKambing()
    {
        $lastUpdated = ListHewan::whereBetween('kategori_id', [7, 12])
            ->where('pengulitan', true)
            ->latest('pengulitan_updated_at')
            ->first();
        return $lastUpdated ? $lastUpdated->pengulitan_updated_at : 'Tidak ada data';
    }

    public static function lastUpdatedPengulitanSapi()
    {
        $lastUpdated = ListHewan::whereBetween('kategori_id', [13, 15])
            ->where('pengulitan', true)
            ->latest('pengulitan_updated_at')
            ->first();
        return $lastUpdated ? $lastUpdated->pengulitan_updated_at : 'Tidak ada data';
    }

    public static function lastUpdatedPenimbanganDomba()
    {
        $lastUpdated = ListHewan::whereBetween('kategori_id', [1, 6])
            ->where('penimbangan', true)
            ->latest('penimbangan_updated_at')
            ->first();
        return $lastUpdated ? $lastUpdated->penimbangan_updated_at : 'Tidak ada data';
    }

    public static function lastUpdatedPenimbanganKambing()
    {
        $lastUpdated = ListHewan::whereBetween('kategori_id', [7, 12])
            ->where('penimbangan', true)
            ->latest('penimbangan_updated_at')
            ->first();
        return $lastUpdated ? $lastUpdated->penimbangan_updated_at : 'Tidak ada data';
    }

    public static function lastUpdatedPenimbanganSapi()
    {
        $lastUpdated = ListHewan::whereBetween('kategori_id', [13, 15])
            ->where('penimbangan', true)
            ->latest('penimbangan_updated_at')
            ->first();
        return $lastUpdated ? $lastUpdated->penimbangan_updated_at : 'Tidak ada data';
    }

    public static function countDaging()
    {
        return ListDistribusi::where('request', '"Daging"')->sum('jumlah');
    }

    public static function countJeroan()
    {
        return ListDistribusi::where('request', '"Jeroan"')->sum('jumlah');
    }

    public static function countKepalaKaki()
    {
        return ListDistribusi::where('request', '"Kepala & Kaki"')->sum('jumlah');
    }

    public static function countShohibulQurban()
    {
        return ListDistribusi::where('shohibul_qurban', true)->count();
    }

    public static function countPenerimaManfaat()
    {
        return ListDistribusi::where('shohibul_qurban', false)->count();
    }

    public static function bungkusDaging()
    {
        return ListDistribusi::where('terbungkus', true)->where('request', '"Daging"')->sum('jumlah');
    }

    public static function bungkusJeroan()
    {
        return ListDistribusi::where('terbungkus', true)->where('request', '"Jeroan"')->sum('jumlah');
    }

    public static function bungkusKepalaKaki()
    {
        return ListDistribusi::where('terbungkus', true)->where('request', '"Kepala & Kaki"')->sum('jumlah');
    }

    public static function distribusiShohibulQurban()
    {
        return ListDistribusi::where('shohibul_qurban', true)->where('terdistribusi', true)->count();
    }

    public static function distribusiPenerimaManfaat()
    {
        return ListDistribusi::where('shohibul_qurban', false)->where('terdistribusi', true)->count();
    }

    public static function lastUpdatedPembungkusan()
    {
        return ListDistribusi::where('terbungkus', true)->latest('updated_at')->first()?->updated_at?->format('d-m-Y H:i:s') ?? 'Tidak ada data';
    }

    public static function lastUpdatedDistribusiQurban()
    {
        return ListDistribusi::where('terdistribusi', true)->latest('updated_at')->first()?->updated_at?->format('d-m-Y H:i:s') ?? 'Tidak ada data';
    }

    public static function calculateProgress($countMethod, $progresMethod)
    {
        $total = self::$countMethod();
        $progres = self::$progresMethod();
        $persentase = $total > 0 ? ($progres / $total) * 100 : 0;

        return [
            'total' => $total,
            'progres' => $progres,
            'persentase' => $persentase,
        ];
    }
}
