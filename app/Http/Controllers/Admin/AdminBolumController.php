<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bolum;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminBolumController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $bolumler = Bolum::query()
            ->when($q !== '', function ($qq) use ($q) {
                $qq->where('bolum_adi', 'like', "%{$q}%");
            })
            ->orderBy('bolum_adi')
            ->paginate(15)
            ->withQueryString();

        return view('admin.bolumler.index', compact('bolumler', 'q'));
    }

    public function create()
    {
        return view('admin.bolumler.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'bolum_adi' => ['required', 'string', 'max:255', Rule::unique('bolumler', 'bolum_adi')],
            'aktif_mi'  => ['nullable', 'boolean'],
        ]);

        Bolum::create([
            'bolum_adi' => trim($data['bolum_adi']),
            'aktif_mi'  => (bool)($data['aktif_mi'] ?? true),
        ]);

        return redirect()->route('admin.bolumler.index')->with('ok', 'Bölüm eklendi.');
    }

    public function edit(Bolum $bolum)
    {
        return view('admin.bolumler.edit', compact('bolum'));
    }

    public function update(Request $request, Bolum $bolum)
    {
        $data = $request->validate([
            'bolum_adi' => ['required', 'string', 'max:255', Rule::unique('bolumler', 'bolum_adi')->ignore($bolum->id)],
            'aktif_mi'  => ['nullable', 'boolean'],
        ]);

        $bolum->update([
            'bolum_adi' => trim($data['bolum_adi']),
            'aktif_mi'  => (bool)($data['aktif_mi'] ?? false),
        ]);

        return redirect()->route('admin.bolumler.index')->with('ok', 'Bölüm güncellendi.');
    }

    public function destroy(Bolum $bolum)
    {
        // İstersen burada "bağlı kullanıcı varsa silme, pasifleştir" kuralı da koyabiliriz.
        $bolum->delete();

        return back()->with('ok', 'Bölüm silindi.');
    }
}
