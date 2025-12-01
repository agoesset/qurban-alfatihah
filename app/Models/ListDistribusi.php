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
