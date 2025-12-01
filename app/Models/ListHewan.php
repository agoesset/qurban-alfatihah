<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class ListHewan extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'kode_hewan', 'kategori_id', 'bobot', 'penyembelihan', 'pengulitan', 'penimbangan',
        'penyembelihan_updated_at', 'pengulitan_updated_at', 'penimbangan_updated_at'
    ];

    protected $casts = [
        'penyembelihan' => 'boolean',
        'pengulitan' => 'boolean',
        'penimbangan' => 'boolean',
        'penyembelihan_updated_at' => 'datetime',
        'pengulitan_updated_at' => 'datetime',
        'penimbangan_updated_at' => 'datetime',
        'bobot' => 'decimal:2',
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

    /**
     * Get the activity log name for this model
     */
    protected function getActivityLogName(): string
    {
        return 'hewan';
    }

    /**
     * Get additional properties to be logged
     */
    protected function getActivityProperties(): array
    {
        return [
            'kode_hewan' => $this->kode_hewan,
            'kategori' => $this->kategori?->nama_kategori,
        ];
    }
}
