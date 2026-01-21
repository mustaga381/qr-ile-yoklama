<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bolum;
use App\Models\DersAcilim;
use App\Models\Kullanici;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class AdminHocaController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q'));

        $hocalar = Kullanici::query()
            ->where('rol', 'hoca')
            ->with('bolum')
            ->withCount([
                // hocanın açtığı benzersiz ders sayısı
                'verdigiAcilimlar as ders_sayisi' => function ($q) {
                    $q->select(DB::raw('count(distinct ders_id)'));
                },
            ])
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        return view('admin.hocalar.index', compact('hocalar', 'q'));
    }

    public function create()
    {
        $bolumler = Bolum::where('aktif_mi', true)->orderBy('bolum_adi')->get();
        return view('admin.hocalar.create', compact('bolumler'));
    }
    public function show(Kullanici $hoca)
    {
        abort_unless($hoca->rol === 'hoca', 404);

        $acilimlar = DersAcilim::query()
            ->with(['ders'])
            ->withCount([
                'kayitlar as ogrenci_sayisi'
            ])
            ->where('hoca_id', $hoca->id)   // ders_acilimlari'nda hoca_id yoksa kolon adını söyle düzeltelim
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        // mini özet
        $toplamAcilim = (clone $acilimlar->getCollection())->count(); // sayfa içi
        // toplam açılım sayısı (tüm kayıtlar)
        $toplamAcilimTum = DersAcilim::where('hoca_id', $hoca->id)->count();

        return view('admin.hocalar.show', compact('hoca', 'acilimlar', 'toplamAcilimTum'));
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'ad_soyad' => ['required', 'string', 'max:255'],
            'eposta' => ['required', 'email', 'max:255', Rule::unique('kullanicilar', 'eposta')],
            'personel_no' => ['required', 'string', 'max:32', Rule::unique('kullanicilar', 'personel_no')],
            'bolum_id' => ['required', 'integer', 'exists:bolumler,id'],
            'sifre' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        Kullanici::create([
            'rol' => 'hoca',
            'ad_soyad' => $data['ad_soyad'],
            'eposta' => $data['eposta'],
            'personel_no' => $data['personel_no'],
            'bolum_id' => $data['bolum_id'],
            'sifre' => Hash::make($data['sifre']),
        ]);

        return redirect()->route('admin.hocalar.index')->with('ok', 'Hoca oluşturuldu.');
    }

    public function edit(Kullanici $hoca)
    {
        abort_unless($hoca->rol === 'hoca', 404);

        $bolumler = Bolum::where('aktif_mi', true)->orderBy('bolum_adi')->get();
        return view('admin.hocalar.edit', compact('hoca', 'bolumler'));
    }

    public function update(Request $request, Kullanici $hoca)
    {
        abort_unless($hoca->rol === 'hoca', 404);

        $data = $request->validate([
            'ad_soyad' => ['required', 'string', 'max:255'],
            'eposta' => ['required', 'email', 'max:255', Rule::unique('kullanicilar', 'eposta')->ignore($hoca->id)],
            'personel_no' => ['required', 'string', 'max:32', Rule::unique('kullanicilar', 'personel_no')->ignore($hoca->id)],
            'bolum_id' => ['required', 'integer', 'exists:bolumler,id'],
            'sifre' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $hoca->update([
            'ad_soyad' => $data['ad_soyad'],
            'eposta' => $data['eposta'],
            'personel_no' => $data['personel_no'],
            'bolum_id' => $data['bolum_id'],
        ]);

        if (!empty($data['sifre'])) {
            $hoca->update(['sifre' => Hash::make($data['sifre'])]);
        }

        return redirect()->route('admin.hocalar.index')->with('ok', 'Hoca güncellendi.');
    }

    public function destroy(Kullanici $hoca)
    {
        abort_unless($hoca->rol === 'hoca', 404);

        $hoca->delete();
        return back()->with('ok', 'Hoca silindi.');
    }
}
