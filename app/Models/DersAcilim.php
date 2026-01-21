<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DersAcilim extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ders_acilimlari';

    protected $fillable = [
        'ders_id',
        'hoca_id',
        'akademik_yil',
        'donem',
        'sube',
        'aktif_mi',
    ];
    protected $appends = ['tam_ad'];
    protected $casts = ['aktif_mi' => 'boolean'];

    public function ders()
    {
        return $this->belongsTo(Ders::class, 'ders_id');
    }

    public function hoca()
    {
        return $this->belongsTo(Kullanici::class, 'hoca_id');
    }

    public function kayitlar()
    {
        return $this->hasMany(DersKayit::class, 'ders_acilim_id');
    }

    public function davetler()
    {
        return $this->hasMany(DersDavet::class, 'ders_acilim_id');
    }

    public function oturumlar()
    {
        return $this->hasMany(DersOturum::class, 'ders_acilim_id');
    }
    public function getTamAdAttribute()
    {
        // 'ders' ilişkisinin yüklü olduğundan emin olmalıyız
        $dersAdi = $this->ders ? $this->ders->ders_adi : 'Bilinmeyen Ders';

        return "{$dersAdi} - {$this->sube}";
    }
}
