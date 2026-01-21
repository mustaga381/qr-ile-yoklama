<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DersOturum extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ders_oturumlari';

    protected $fillable = [
        'ders_acilim_id',
        'oturum_tarihi',
        'baslangic_zamani',
        'bitis_zamani',
        'baslik',
        'hoca_id',
    ];

    protected $casts = [
        'oturum_tarihi' => 'date',
        'baslangic_zamani' => 'datetime',
        'bitis_zamani' => 'datetime',
    ];

    public function dersAcilim()
    {
        return $this->belongsTo(DersAcilim::class, 'ders_acilim_id');
    }

    public function olusturanHoca()
    {
        return $this->belongsTo(Kullanici::class, 'hoca_id');
    }

    public function yoklamaPencereleri()
    {
        return $this->hasMany(YoklamaPencere::class, 'ders_oturum_id');
    }

    public function yoklamalar()
    {
        return $this->hasMany(Yoklama::class, 'ders_oturum_id');
    }
}
