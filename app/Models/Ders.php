<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ders extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'dersler';

    protected $fillable = [
        'ders_kodu',
        'ders_adi',
        'aciklama',
        'hoca_id',
    ];
    public function hoca()
    {
        return $this->belongsTo(Kullanici::class, 'hoca_id');
    }
    public function acilimlar()
    {
        return $this->hasMany(DersAcilim::class, 'ders_id');
    }
}
