<?php

namespace App\Http\Controllers\Hoca;

use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\Ders;
use Illuminate\Http\Request;

class DersController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q'));

        $dersler = Ders::query()
            ->where('hoca_id', $request->user()->id)
            ->when($q, fn($qr) => $qr->where(function ($w) use ($q) {
                $w->where('ders_adi', 'like', "%{$q}%")
                    ->orWhere('ders_kodu', 'like', "%{$q}%");
            }))
            ->orderBy('ders_adi')
            ->paginate(12)
            ->withQueryString();

        return view('hoca.dersler.index', compact('dersler', 'q'));
    }

    public function create()
    {
        return view('hoca.dersler.create');
    }


    public function store(Request $request)
    {
        $hoca = $request->user();

        $data = $request->validate([
            'ders_kodu' => [
                'required',
                'string',
                'max:32',
                Rule::unique('dersler', 'ders_kodu')
                    ->where(fn($q) => $q->where('hoca_id', $hoca->id)->whereNull('deleted_at')),
            ],
            'ders_adi' => ['required', 'string', 'max:255'],
            'aciklama' => ['nullable', 'string'],
        ]);

        $eski = Ders::withTrashed()
            ->where('hoca_id', $hoca->id)
            ->where('ders_kodu', $data['ders_kodu'])
            ->first();

        if ($eski && $eski->trashed()) {
            $eski->restore();
            $eski->update([
                'ders_adi'  => $data['ders_adi'],
                'aciklama'  => $data['aciklama'] ?? null,
            ]);

            return redirect()->route('hoca.dersler.index')->with('ok', 'Silinen ders geri yüklendi.');
        }
        Ders::create([
            'hoca_id'   => $hoca->id,
            'ders_kodu' => $data['ders_kodu'],
            'ders_adi'  => $data['ders_adi'],
            'aciklama'  => $data['aciklama'] ?? null,
        ]);

        return redirect()->route('hoca.dersler.index')->with('ok', 'Ders oluşturuldu.');
    }


    public function edit(Ders $dersler)
    {
        // route-model binding param adı "dersler" olduğu için böyle
        return view('hoca.dersler.edit', ['ders' => $dersler]);
    }

    public function update(Request $request, Ders $dersler)
    {
        $data = $request->validate([
            'ders_kodu' => ['required', 'string', 'max:50', 'unique:dersler,ders_kodu,' . $dersler->id],
            'ders_adi'  => ['required', 'string', 'max:255'],
            'aciklama'  => ['nullable', 'string'],
        ]);

        $dersler->update($data);

        return redirect()->route('hoca.dersler.index')->with('ok', 'Ders güncellendi.');
    }

    public function destroy(Ders $dersler)
    {
        $dersler->delete();

        return redirect()->route('hoca.dersler.index')->with('ok', 'Ders silindi.');
    }
}
