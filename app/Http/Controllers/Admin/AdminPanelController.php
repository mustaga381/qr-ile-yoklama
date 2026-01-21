<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kullanici;
use App\Models\DersAcilim;
use App\Models\Yoklama;

class AdminPanelController extends Controller
{
    public function __invoke()
    {
        $stats = [
            'ogrenci' => Kullanici::where('rol', 'ogrenci')->count(),
            'hoca' => Kullanici::where('rol', 'hoca')->count(),
            'acilim' => DersAcilim::count(),
            'yoklama' => class_exists(Yoklama::class) ? Yoklama::count() : 0,
        ];

        return view('admin.panel', compact('stats'));
    }
}
