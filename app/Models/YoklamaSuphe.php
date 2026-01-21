<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YoklamaSuphe extends Model
{
    use HasFactory;

    protected $table = 'yoklama_supheleri';

    protected $fillable = ['yoklama_id', 'tur', 'meta'];

    protected $casts = ['meta' => 'array'];

    public function yoklama()
    {
        return $this->belongsTo(Yoklama::class, 'yoklama_id');
    }
}
