<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Kullanici extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'kullanicilar';

    protected $fillable = [
        'ad_soyad',
        'eposta',
        'sifre',
        'rol',
        'ogrenci_no',
        'personel_no',
        'bolum_id',
        'aktif_mi',
        'eposta_dogrulandi_at',
        'hatirla_token',
    ];

    protected $hidden = ['sifre', 'hatirla_token'];

    protected $casts = [
        'aktif_mi' => 'boolean',
        'eposta_dogrulandi_at' => 'datetime',
    ];

    public function getAuthPassword()
    {
        return $this->sifre;
    }

    public function getRememberTokenName()
    {
        return 'hatirla_token';
    }
    public function bolum()
    {
        return $this->belongsTo(Bolum::class, 'bolum_id');
    }
    public function setSifreAttribute($value)
    {
        if (!$value) return;
        $this->attributes['sifre'] = Hash::needsRehash($value) ? Hash::make($value) : $value;
    }

    // İlişkiler
    public function dersler()
    {
        return $this->hasMany(Ders::class, 'hoca_id');
    }

    public function verdigiAcilimlar()
    {
        return $this->hasMany(DersAcilim::class, 'hoca_id');
    }

    public function dersKayitlari()
    {
        return $this->hasMany(DersKayit::class, 'ogrenci_id');
    }

    public function cihazlar()
    {
        return $this->hasMany(Cihaz::class, 'kullanici_id');
    }

    public function yoklamalar()
    {
        return $this->hasMany(Yoklama::class, 'ogrenci_id');
    }
}
