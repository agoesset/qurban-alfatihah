<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListDistribusi extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'nama',
        'shohibul_qurban',
        'jumlah',
        'request',
        'alamat',
        'terbungkus',
        'terdistribusi',
    ];

    protected $casts = [
        'request' => 'array',
        'shohibul_qurban' => 'boolean',
        'terbungkus' => 'boolean',
        'terdistribusi' => 'boolean',
        'jumlah' => 'integer',
    ];

    /**
     * Relationship to DistribusiDetail
     */
    public function details()
    {
        return $this->hasMany(DistribusiDetail::class);
    }

    /**
     * Get total allocated weight from all details
     */
    public function getTotalAllocatedWeightAttribute(): float
    {
        return $this->details()->sum('berat');
    }

    /**
     * Get breakdown by meat type
     */
    public function getMeatBreakdownAttribute(): array
    {
        $breakdown = [];
        foreach ($this->details as $detail) {
            $jenis = $detail->hewanMeatPart->jenis_bagian;
            if (!isset($breakdown[$jenis])) {
                $breakdown[$jenis] = 0;
            }
            $breakdown[$jenis] += $detail->berat;
        }
        return $breakdown;
    }

    /**
     * Get the activity log name for this model
     */
    protected function getActivityLogName(): string
    {
        return 'distribusi';
    }

    /**
     * Get additional properties to be logged
     */
    protected function getActivityProperties(): array
    {
        return [
            'nama' => $this->nama,
            'shohibul_qurban' => $this->shohibul_qurban,
            'jumlah' => $this->jumlah,
        ];
    }
}
