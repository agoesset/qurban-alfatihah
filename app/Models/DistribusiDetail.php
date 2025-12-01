<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DistribusiDetail extends Model
{
    protected $fillable = [
        'list_distribusi_id',
        'hewan_meat_part_id',
        'berat',
    ];

    protected $casts = [
        'berat' => 'decimal:2',
    ];

    /**
     * Relationship to ListDistribusi
     */
    public function listDistribusi(): BelongsTo
    {
        return $this->belongsTo(ListDistribusi::class);
    }

    /**
     * Relationship to HewanMeatPart
     */
    public function hewanMeatPart(): BelongsTo
    {
        return $this->belongsTo(HewanMeatPart::class);
    }

    /**
     * Get the hewan through meat part
     */
    public function getHewanAttribute()
    {
        return $this->hewanMeatPart?->listHewan;
    }

    /**
     * Boot method to handle stock allocation
     */
    protected static function boot()
    {
        parent::boot();

        // When distribusi detail is deleted, release the stock
        static::deleting(function ($detail) {
            $detail->hewanMeatPart->releaseStock($detail->berat);
        });
    }
}
