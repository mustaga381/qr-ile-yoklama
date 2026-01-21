<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Yoklama extends Model
{
    use HasFactory;

    protected $table = 'yoklamalar';

    protected $fillable = [
        'ders_oturum_id',
        'ogrenci_id',
        'yoklama_pencere_id',
        'cihaz_id',
        'ip_adresi',
        'user_agent',
        'durum',
        'isaretlenme_zamani',
        'qr_adim_no',
    ];

    protected $casts = [
        'isaretlenme_zamani' => 'datetime',
    ];

    public function oturum()
    {
        return $this->belongsTo(DersOturum::class, 'ders_oturum_id');
    }

    public function ogrenci()
    {
        return $this->belongsTo(Kullanici::class, 'ogrenci_id');
    }

    public function pencere()
    {
        return $this->belongsTo(YoklamaPencere::class, 'yoklama_pencere_id');
    }

    public function cihaz()
    {
        return $this->belongsTo(Cihaz::class, 'cihaz_id');
    }

    public function supheler()
    {
        return $this->hasMany(YoklamaSuphe::class, 'yoklama_id');
    }
}
