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

        // Auto-generate kode_hewan before creating
        static::creating(function ($model) {
            if (empty($model->kode_hewan) && $model->kategori_id) {
                $model->kode_hewan = self::generateKodeHewan($model->kategori_id);
            }
        });

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

    /**
     * Generate unique kode hewan based on kategori
     */
    public static function generateKodeHewan(int $kategoriId): string
    {
        $kategori = Kategori::find($kategoriId);

        if (!$kategori) {
            throw new \Exception('Kategori not found');
        }

        // Determine prefix based on kategori name
        $prefix = match(true) {
            str_starts_with($kategori->nama_kategori, 'Domba') => 'DMB',
            str_starts_with($kategori->nama_kategori, 'Kambing') => 'KMB',
            str_starts_with($kategori->nama_kategori, 'Sapi') => 'SPI',
            default => 'HWN'
        };

        // Get the last number for this prefix
        $lastHewan = self::where('kode_hewan', 'like', "{$prefix}-%")
            ->orderByRaw("CAST(SUBSTRING(kode_hewan, 5) AS UNSIGNED) DESC")
            ->first();

        $nextNumber = 1;
        if ($lastHewan) {
            $lastNumber = (int) substr($lastHewan->kode_hewan, 4);
            $nextNumber = $lastNumber + 1;
        }

        return $prefix . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    /**
     * Relationship to HewanMeatPart
     */
    public function meatParts()
    {
        return $this->hasMany(HewanMeatPart::class);
    }

    /**
     * Get available stock summary
     */
    public function getAvailableStockAttribute(): array
    {
        return [
            'Daging' => $this->meatParts()->ofType('Daging')->sum('berat_tersedia'),
            'Jeroan' => $this->meatParts()->ofType('Jeroan')->sum('berat_tersedia'),
            'Kepala & Kaki' => $this->meatParts()->ofType('Kepala & Kaki')->sum('berat_tersedia'),
            'Buntut' => $this->meatParts()->ofType('Buntut')->sum('berat_tersedia'),
        ];
    }

    // ========== QUERY SCOPES ==========

    /**
     * Scope to filter by jenis hewan (domba, kambing, sapi)
     */
    public function scopeOfJenis($query, string $jenis)
    {
        $pattern = match($jenis) {
            'domba' => 'Domba%',
            'kambing' => 'Kambing%',
            'sapi' => 'Sapi%',
            default => null
        };

        if (!$pattern) {
            return $query;
        }

        return $query->whereHas('kategori', function($q) use ($pattern) {
            $q->where('nama_kategori', 'like', $pattern);
        });
    }

    /**
     * Scope to filter by penyembelihan status
     */
    public function scopeDisembelih($query, bool $status = true)
    {
        return $query->where('penyembelihan', $status);
    }

    /**
     * Scope to filter by pengulitan status
     */
    public function scopeDikuliti($query, bool $status = true)
    {
        return $query->where('pengulitan', $status);
    }

    /**
     * Scope to filter by penimbangan status
     */
    public function scopeDitimbang($query, bool $status = true)
    {
        return $query->where('penimbangan', $status);
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
