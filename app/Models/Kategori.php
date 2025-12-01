<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use LogsActivity;

    protected $fillable = ['nama_kategori', 'image'];

    public function listHewans()
    {
        return $this->hasMany(ListHewan::class, 'kategori_id');
    }

    /**
     * Get the activity log name for this model
     */
    protected function getActivityLogName(): string
    {
        return 'kategori';
    }

    /**
     * Get additional properties to be logged
     */
    protected function getActivityProperties(): array
    {
        return [
            'nama_kategori' => $this->nama_kategori,
        ];
    }
}
