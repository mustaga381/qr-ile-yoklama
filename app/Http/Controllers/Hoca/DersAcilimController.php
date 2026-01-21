<?php

namespace App\Http\Controllers\Hoca;

use App\Http\Controllers\Controller;
use App\Models\Ders;
use App\Models\DersAcilim;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DersAcilimController extends Controller
{
    public function show(DersAcilim $dersAcilim)
    {
        $dersAcilim->load([
            'ders',
            'kayitlar.ogrenci',
            'davetler',
        ]);

        return view('hoca.ders_acilimlari.show', [
            'acilim'   => $dersAcilim,
            'kayitlar' => $dersAcilim->kayitlar,
            'davetler' => $dersAcilim->davetler,
        ]);
    }
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q'));

        $user = $request->user();

        $acilimlar = DersAcilim::query()
            ->with('ders')
            ->withCount('kayitlar')
            ->when($user->rol !== 'admin', fn($qr) => $qr->where('hoca_id', $user->id))
            ->when($q, fn($qr) => $qr->where(function ($w) use ($q) {
                $w->where('akademik_yil', 'like', "%{$q}%")
                    ->orWhere('sube', 'like', "%{$q}%")
                    ->orWhereHas(
                        'ders',
                        fn($d) => $d
                            ->where('ders_adi', 'like', "%{$q}%")
                            ->orWhere('ders_kodu', 'like', "%{$q}%")
                    );
            }))
            ->orderByDesc('akademik_yil')
            ->orderBy('donem')
            ->orderBy('sube')
            ->paginate(12)
            ->withQueryString();
        $istatistik = [
            'toplam' => $acilimlar->count(),
            'aktif'  => (clone $acilimlar)->where('aktif_mi', true)->count(),
            'pasif'  => (clone $acilimlar)->where('aktif_mi', false)->count(),
        ];
        return view('hoca.ders_acilimlari.index', compact('acilimlar', 'q', 'istatistik'));
    }

    public function create(Request $request)
    {
        $user = $request->user();
        $dersler = Ders::orderBy('ders_adi')->where('hoca_id', $user->id)->get(['id', 'ders_kodu', 'ders_adi']);
        $seciliDersId = $request->integer('ders_id');

        $akademikYillar = $this->akademikYilListesi();

        return view('hoca.ders_acilimlari.create', compact('dersler', 'seciliDersId', 'akademikYillar'));
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'ders_id'       => ['required', 'exists:dersler,id'],
            'akademik_yil'  => ['required', 'regex:/^\d{4}-\d{4}$/'],
            'donem'         => ['required', Rule::in(['guz', 'bahar', 'yaz'])],
            'sube'          => ['required', 'string', 'max:20'],
            'aktif_mi'      => ['nullable', 'boolean'],
        ]);

        // Aynı ders + yıl + dönem + şube tekrar açılmasın
        $uniqueRule = Rule::unique('ders_acilimlari')
            ->where(
                fn($q) => $q->where('ders_id', $data['ders_id'])
                    ->where('akademik_yil', $data['akademik_yil'])
                    ->where('donem', $data['donem'])
                    ->where('sube', $data['sube'])
            );

        $request->validate([
            'ders_id' => [$uniqueRule],
        ], [
            'ders_id.unique' => 'Bu ders için bu akademik yıl / dönem / şube zaten mevcut.',
        ]);

        DersAcilim::create([
            'ders_id'      => $data['ders_id'],
            'hoca_id'      => $user->id,
            'akademik_yil' => $data['akademik_yil'],
            'donem'        => $data['donem'],
            'sube'         => $data['sube'],
            'aktif_mi'     => (bool)($data['aktif_mi'] ?? true),
        ]);

        return redirect()->route('hoca.ders_acilimlari.index')->with('ok', 'Ders açılımı oluşturuldu.');
    }

    public function edit(DersAcilim $dersAcilim)
    {
        $dersler = Ders::orderBy('ders_adi')->get(['id', 'ders_kodu', 'ders_adi']);
        $akademikYillar = $this->akademikYilListesi();
        return view('hoca.ders_acilimlari.edit', compact('dersAcilim', 'dersler', 'akademikYillar'));
    }

    public function update(Request $request, DersAcilim $dersAcilim)
    {
        $data = $request->validate([
            'ders_id'       => ['required', 'exists:dersler,id'],
            'akademik_yil'  => ['required', 'regex:/^\d{4}-\d{4}$/'],
            'donem'         => ['required', Rule::in(['guz', 'bahar', 'yaz'])],
            'sube'          => ['required', 'string', 'max:20'],
            'aktif_mi'      => ['nullable', 'boolean'],
        ]);

        $uniqueRule = Rule::unique('ders_acilimlari')
            ->ignore($dersAcilim->id)
            ->where(
                fn($q) => $q->where('ders_id', $data['ders_id'])
                    ->where('akademik_yil', $data['akademik_yil'])
                    ->where('donem', $data['donem'])
                    ->where('sube', $data['sube'])
            );

        $request->validate([
            'ders_id' => [$uniqueRule],
        ], [
            'ders_id.unique' => 'Bu ders için bu akademik yıl / dönem / şube zaten mevcut.',
        ]);

        $dersAcilim->update([
            'ders_id'      => $data['ders_id'],
            'akademik_yil' => $data['akademik_yil'],
            'donem'        => $data['donem'],
            'sube'         => $data['sube'],
            'aktif_mi'     => (bool)($data['aktif_mi'] ?? false),
        ]);

        return redirect()->route('hoca.ders_acilimlari.index')->with('ok', 'Ders açılımı güncellendi.');
    }

    public function destroy(DersAcilim $dersAcilim)
    {
        $dersAcilim->delete(); // soft delete
        return redirect()->route('hoca.ders_acilimlari.index')->with('ok', 'Ders açılımı silindi.');
    }

    private function akademikYilListesi(): array
    {
        $yil = now()->year; // örn 2025
        $liste = [];
        for ($i = -1; $i <= 2; $i++) {
            $bas = $yil + $i;
            $liste[] = "{$bas}-" . ($bas + 1);
        }
        return $liste;
    }
}
