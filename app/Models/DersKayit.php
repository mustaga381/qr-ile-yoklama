<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DersKayit extends Model
{
    use HasFactory;

    protected $table = 'ders_kayitlari';

    protected $fillable = [
        'ders_acilim_id',
        'ogrenci_id',
        'durum',
        'kayit_tarihi',
        'kayit_sekli'
    ];

    protected $casts = ['kayit_tarihi' => 'datetime'];

    public function acilim()
    {
        return $this->belongsTo(DersAcilim::class, 'ders_acilim_id');
    }

    public function ogrenci()
    {
        return $this->belongsTo(Kullanici::class, 'ogrenci_id');
    }
}
