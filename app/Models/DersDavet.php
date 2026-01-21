<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DersDavet extends Model
{
    protected $table = 'ders_davetleri';

    protected $fillable = [
        'ders_acilim_id',
        'olusturan_id',
        'token',
        'hedef_ogrenci_id',
        'hedef_eposta',
        'son_gecerlilik_tarihi',
        'max_kullanim',
        'kullanim_sayisi',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'son_gecerlilik_tarihi' => 'datetime',
        'max_kullanim' => 'integer',
        'kullanim_sayisi' => 'integer',
    ];

    // --- ilişkiler ---

    public function dersAcilim()
    {
        return $this->belongsTo(DersAcilim::class, 'ders_acilim_id');
    }

    public function olusturan()
    {
        return $this->belongsTo(Kullanici::class, 'olusturan_id');
    }

    public function hedefOgrenci()
    {
        return $this->belongsTo(Kullanici::class, 'hedef_ogrenci_id');
    }

    // --- yardımcılar ---

    public function sureDolduMu(): bool
    {
        return $this->son_gecerlilik_tarihi ? now()->gt($this->son_gecerlilik_tarihi) : false;
    }

    public function kullanimDolduMu(): bool
    {
        return $this->kullanim_sayisi >= $this->max_kullanim;
    }

    public function aktifMi(): bool
    {
        if (!$this->aktif) return false;
        if ($this->sureDolduMu()) return false;
        if ($this->kullanimDolduMu()) return false;
        return true;
    }
}
