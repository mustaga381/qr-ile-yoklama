<?php

namespace App\Http\Controllers\Hoca;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\DersAcilim;
use App\Models\DersOturum;
use App\Models\YoklamaPencere;
use App\Models\Yoklama;

class HocaPanelController extends Controller
{
    public function __invoke(Request $request)
    {
        $hocaId = auth()->id();

        // hocanın ders açılımları
        $dersSayisi = DersAcilim::where('hoca_id', $hocaId)->count();

        // hocanın oturumları
        $oturumSayisi = DersOturum::where('hoca_id', $hocaId)->count();

        // aktif yoklama pencereleri (hocanın açtığı)
        $aktifPencereler = YoklamaPencere::query()
            ->where('acani_hoca_id', $hocaId)
            ->where('durum', 'acik')
            ->with(['oturum.dersAcilim'])
            ->orderByDesc('id')
            ->take(3)
            ->get();

        // son yoklamalar (hocanın ders oturumlarına ait)
        $sonYoklamalar = Yoklama::query()
            ->whereHas('oturum', fn($q) => $q->where('hoca_id', $hocaId))
            ->with(['oturum.dersAcilim', 'ogrenci'])
            ->orderByDesc('isaretlenme_zamani')
            ->take(8)
            ->get();

        $stats = [
            'ders_sayisi' => $dersSayisi,
            'oturum_sayisi' => $oturumSayisi,
            'aktif_pencere' => $aktifPencereler->count(),
            'supheli_sayi' => (int) Yoklama::whereHas('oturum', fn($q) => $q->where('hoca_id', $hocaId))
                ->where('durum', 'supheli')
                ->count(),
        ];

        return view('hoca.panel', compact('stats', 'aktifPencereler', 'sonYoklamalar'));
    }
}
