<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $fillable = ['nama_kategori', 'image'];

    public function listHewans()
    {
        return $this->hasMany(ListHewan::class, 'kategori_id');
    }
}
