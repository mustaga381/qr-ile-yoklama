<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cihaz extends Model
{
    use HasFactory;

    protected $table = 'cihazlar';

    protected $fillable = [
        'kullanici_id',
        'cihaz_uuid',
        'etiket',
        'platform',
        'user_agent',
        'ilk_gorulme',
        'son_gorulme',
        'guvenilir_mi',
    ];

    protected $casts = [
        'ilk_gorulme' => 'datetime',
        'son_gorulme' => 'datetime',
        'guvenilir_mi' => 'boolean',
    ];

    public function kullanici()
    {
        return $this->belongsTo(Kullanici::class, 'kullanici_id');
    }

    public function yoklamalar()
    {
        return $this->hasMany(Yoklama::class, 'cihaz_id');
    }
}
