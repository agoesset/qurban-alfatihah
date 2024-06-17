<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class ListHewan extends Model
{
    // Jika Anda menggunakan soft deletes
    use SoftDeletes;

    protected $fillable = [
        'kode_hewan', 'kategori_id', 'bobot', 'penyembelihan', 'pengulitan', 'penimbangan',
        'penyembelihan_updated_at', 'pengulitan_updated_at', 'penimbangan_updated_at'
    ];

    protected $dates = [
        'penyembelihan_updated_at', 'pengulitan_updated_at', 'penimbangan_updated_at'
    ];

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($model) {
            if ($model->isDirty('penyembelihan')) {
                $model->penyembelihan_updated_at = Carbon::now();
            }

            if ($model->isDirty('pengulitan')) {
                $model->pengulitan_updated_at = Carbon::now();
            }

            if ($model->isDirty('penimbangan')) {
                $model->penimbangan_updated_at = Carbon::now();
            }
        });
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
}
