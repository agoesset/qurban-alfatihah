<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HewanMeatPart extends Model
{
    protected $fillable = [
        'list_hewan_id',
        'jenis_bagian',
        'berat_total',
        'berat_tersedia',
    ];

    protected $casts = [
        'berat_total' => 'decimal:2',
        'berat_tersedia' => 'decimal:2',
    ];

    /**
     * Relationship to ListHewan
     */
    public function listHewan(): BelongsTo
    {
        return $this->belongsTo(ListHewan::class);
    }

    /**
     * Relationship to DistribusiDetails
     */
    public function distribusiDetails(): HasMany
    {
        return $this->hasMany(DistribusiDetail::class);
    }

    /**
     * Check if there's enough stock available
     */
    public function hasEnoughStock(float $amount): bool
    {
        return $this->berat_tersedia >= $amount;
    }

    /**
     * Allocate stock for distribution
     */
    public function allocateStock(float $amount): bool
    {
        if (!$this->hasEnoughStock($amount)) {
            return false;
        }

        $this->decrement('berat_tersedia', $amount);
        return true;
    }

    /**
     * Release allocated stock (if distribution cancelled)
     */
    public function releaseStock(float $amount): void
    {
        $this->increment('berat_tersedia', $amount);
    }

    /**
     * Get percentage of stock used
     */
    public function getStockUsagePercentageAttribute(): float
    {
        if ($this->berat_total == 0) {
            return 0;
        }

        return round((($this->berat_total - $this->berat_tersedia) / $this->berat_total) * 100, 2);
    }

    /**
     * Scope to filter by jenis bagian
     */
    public function scopeOfType($query, string $jenisBagian)
    {
        return $query->where('jenis_bagian', $jenisBagian);
    }

    /**
     * Scope to get only available stock
     */
    public function scopeAvailable($query)
    {
        return $query->where('berat_tersedia', '>', 0);
    }
}
