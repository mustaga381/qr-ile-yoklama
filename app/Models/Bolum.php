<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bolum extends Model
{
    protected $table = 'bolumler';

    protected $fillable = [
        'bolum_adi',
        'aktif_mi',
    ];

    public function kullanicilar()
    {
        return $this->hasMany(Kullanici::class, 'bolum_id');
    }
}
