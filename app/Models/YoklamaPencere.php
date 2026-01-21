<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YoklamaPencere extends Model
{
    use HasFactory;

    protected $table = 'yoklama_pencereleri';

    protected $fillable = [
        'ders_oturum_id',
        'acani_hoca_id',
        'baslangic_zamani',
        'bitis_zamani',
        'yenileme_saniye',
        'gizli_anahtar',
        'durum',
    ];

    protected $casts = [
        'baslangic_zamani' => 'datetime',
        'bitis_zamani' => 'datetime',
    ];

    public function oturum()
    {
        return $this->belongsTo(DersOturum::class, 'ders_oturum_id');
    }

    public function acanHoca()
    {
        return $this->belongsTo(Kullanici::class, 'acani_hoca_id');
    }

    public function yoklamalar()
    {
        return $this->hasMany(Yoklama::class, 'yoklama_pencere_id');
    }
}
