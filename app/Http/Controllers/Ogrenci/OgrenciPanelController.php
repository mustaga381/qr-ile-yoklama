<?php

namespace App\Http\Controllers\Ogrenci;

use App\Http\Controllers\Controller;
use App\Models\DersKayit;
use App\Models\Yoklama;
use Illuminate\Http\Request;

class OgrenciPanelController extends Controller
{
    public function __invoke()
    {
        $ogrenciId = auth()->id();

        // 1) Kayıtlı ders sayısı
        $dersSayisi = DersKayit::where('ogrenci_id', $ogrenciId)->count();

        // 2) Yoklama istatistikleri (tek sorguda)
        $yoklamaStats = Yoklama::where('ogrenci_id', $ogrenciId)
            ->selectRaw("COUNT(*) as yoklama_toplam")
            ->selectRaw("SUM(CASE WHEN durum = 'supheli' THEN 1 ELSE 0 END) as supheli")
            ->first();

        $stats = [
            'ders_sayisi' => $dersSayisi,
            'yoklama_toplam' => (int)($yoklamaStats->yoklama_toplam ?? 0),
            'supheli' => (int)($yoklamaStats->supheli ?? 0),
        ];

        return view('ogrenci.panel', compact('stats'));
    }
    public function yoklamalarim(Request $request)
    {
        $ogrenciId = auth()->id();

        $durum = $request->query('durum');
        $q     = $request->query('q');

        $query = Yoklama::query()
            ->where('ogrenci_id', $ogrenciId)
            ->with([
                'oturum.dersAcilim.ders',
                'oturum.dersAcilim.hoca',
            ])
            ->when($durum, fn($qq) => $qq->where('durum', $durum))
            ->when($q, function ($qq) use ($q) {
                $qq->whereHas('oturum', function ($o) use ($q) {
                    $o->where('baslik', 'like', "%{$q}%")
                        ->orWhereHas('dersAcilim', function ($da) use ($q) {
                            $da->Where('tam_ad', 'like', "%{$q}%")
                                ->orWhereHas('ders', fn($d) => $d->where('ad', 'like', "%{$q}%"));
                        });
                });
            })
            ->orderByDesc('isaretlenme_zamani');

        $items = $query->paginate(20)->withQueryString();


        // dd($items->toArray());
        return view('ogrenci.yoklama.index', compact('items', 'durum', 'q'));
    }
}
