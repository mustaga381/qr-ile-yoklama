<?php

namespace App\Http\Controllers\Hoca;

use App\Http\Controllers\Controller;
use App\Models\DersAcilim;
use App\Models\DersDavet;
use App\Models\Kullanici;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HocaDavetController extends Controller
{
    public function olustur(Request $request, DersAcilim $acilim)
    {
        $user = $request->user();
        abort_unless($user && in_array($user->rol, ['hoca', 'admin'], true), 403);

        // İstersen sadece kendi açılışına davet üretsin:
        // abort_unless($acilim->hoca_id === $user->id || $user->rol === 'admin', 403);

        $data = $request->validate([
            'max_kullanim'       => ['required', 'integer', 'min:1', 'max:200'],
            'gecerlilik_dakika'  => ['nullable', 'integer', 'min:5', 'max:10080'], // 7 gün
            'hedef_ogrenci_no'   => ['nullable', 'string', 'max:32'],
            'hedef_eposta'       => ['nullable', 'email', 'max:255'],
        ]);

        // hedef öğrenci no -> hedef_ogrenci_id
        $hedefOgrenciId = null;
        if (!empty($data['hedef_ogrenci_no'])) {
            $ogrenci = Kullanici::where('rol', 'ogrenci')
                ->where('ogrenci_no', $data['hedef_ogrenci_no'])
                ->first();

            if (!$ogrenci) {
                return back()->withErrors(['hedef_ogrenci_no' => 'Bu öğrenci numarasıyla öğrenci bulunamadı.'])->withInput();
            }

            $hedefOgrenciId = $ogrenci->id;
        }

        // geçerlilik süresi
        $sonTarih = null;
        if (!empty($data['gecerlilik_dakika'])) {
            $sonTarih = now()->addMinutes((int) $data['gecerlilik_dakika']);
        }

        // token benzersiz olsun
        $token = $this->benzersizTokenUret();

        DersDavet::create([
            'ders_acilim_id'        => $acilim->id,
            'olusturan_id'          => $user->id,
            'token'                 => $token,
            'hedef_ogrenci_id'      => $hedefOgrenciId,
            'hedef_eposta'          => $data['hedef_eposta'] ?? null,
            'son_gecerlilik_tarihi' => $sonTarih,
            'max_kullanim'          => (int) $data['max_kullanim'],
            'kullanim_sayisi'       => 0,
            'aktif'                 => true,
        ]);

        return back()->with('ok', 'Davet oluşturuldu.');
    }

    public function pasifEt(Request $request, DersAcilim $acilim, DersDavet $davet)
    {
        $user = $request->user();
        abort_unless($user && in_array($user->rol, ['hoca', 'admin'], true), 403);

        // URL ile başka açılışın davetine erişmeyi engelle
        abort_unless((int) $davet->ders_acilim_id === (int) $acilim->id, 404);

        // İstersen sadece kendi açılışının davetini pasif etsin:
        // abort_unless($acilim->hoca_id === $user->id || $user->rol === 'admin', 403);

        $davet->update(['aktif' => false]);

        return back()->with('ok', 'Davet pasife alındı.');
    }

    // Opsiyonel: geri açmak istersen
    public function aktifEt(Request $request, DersAcilim $acilim, DersDavet $davet)
    {
        $user = $request->user();
        abort_unless($user && in_array($user->rol, ['hoca', 'admin'], true), 403);
        abort_unless((int) $davet->ders_acilim_id === (int) $acilim->id, 404);

        $davet->update(['aktif' => true]);

        return back()->with('ok', 'Davet tekrar aktif edildi.');
    }

    // Opsiyonel: tamamen silmek istersen
    public function sil(Request $request, DersAcilim $acilim, DersDavet $davet)
    {
        $user = $request->user();
        abort_unless($user && in_array($user->rol, ['hoca', 'admin'], true), 403);
        abort_unless((int) $davet->ders_acilim_id === (int) $acilim->id, 404);

        $davet->delete();

        return back()->with('ok', 'Davet silindi.');
    }

    private function benzersizTokenUret(): string
    {
        // küçük bir loop ile çakışmayı sıfıra yakın yapıyoruz
        do {
            $token = Str::random(48);
        } while (DersDavet::where('token', $token)->exists());

        return $token;
    }
}
