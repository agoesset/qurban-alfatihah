<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListDistribusi extends Model
{
    use HasFactory;

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
    ];
}
