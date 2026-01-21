<?php

use App\Http\Controllers\Admin\AdminBolumController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\KayitController;
use App\Http\Controllers\Auth\GirisController;
use App\Http\Controllers\Auth\CikisController;

use App\Http\Controllers\DavetController;
use App\Http\Controllers\Hesap\ProfilController;

use App\Http\Controllers\Hoca\DersAcilimController;
use App\Http\Controllers\Hoca\DersController;
use App\Http\Controllers\Hoca\HocaKayitController;
use App\Http\Controllers\Hoca\HocaPanelController;
use App\Http\Controllers\Hoca\HocaDavetController;
use App\Http\Controllers\Hoca\HocaYoklamaController;
use App\Http\Controllers\Ogrenci\OgrenciDersController;
use App\Http\Controllers\Ogrenci\OgrenciKayitController;
use App\Http\Controllers\Ogrenci\OgrenciPanelController;
use App\Http\Controllers\Ogrenci\OgrenciYoklamaController;

use App\Http\Controllers\Admin\AdminPanelController;
use App\Http\Controllers\Admin\AdminHocaController;
use App\Http\Controllers\Admin\AdminOgrenciController;
use App\Http\Controllers\Admin\AdminDersAcilimController;
use App\Http\Controllers\Admin\AdminYoklamaController;

Route::middleware('guest')->group(function () {
    Route::get('/kayit', [KayitController::class, 'form'])->name('kayit.form');
    Route::post('/kayit', [KayitController::class, 'kaydet'])->name('kayit.kaydet');

    Route::get('/giris', [GirisController::class, 'form'])->name('giris.form');
    Route::post('/giris', [GirisController::class, 'giris'])->name('giris.yap');
});

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        $user = auth()->user();
        return match ($user->rol) {
            'hoca' => redirect()->route('hoca.panel'),
            'admin'      => redirect()->route('admin.panel'),
            'ogrenci'      => redirect()->route('ogrenci.panel'),
            default        => abort(403),
        };
    });
    Route::get('/profil', [ProfilController::class, 'goster'])->name('profil.goster');
    Route::patch('/profil', [ProfilController::class, 'guncelle'])->name('profil.guncelle');
    Route::patch('/profil/sifre', [ProfilController::class, 'sifreGuncelle'])->name('profil.sifre');

    Route::post('/cikis', [CikisController::class, 'cikis'])->name('cikis');
    /**
     * Admin
     */
    Route::prefix('admin')->name('admin.')->middleware(['auth', 'rol:admin'])->group(function () {

        Route::get('/panel', AdminPanelController::class)->name('panel');

        Route::resource('/hocalar', AdminHocaController::class)->parameters(['hocalar' => 'hoca'])->names('hocalar');
        Route::resource('bolumler', AdminBolumController::class)->parameters(['bolumler' => 'bolum'])->names('bolumler');
        // Öğrenci (şimdilik: liste + detay)
        Route::get('/ogrenciler', [AdminOgrenciController::class, 'index'])->name('ogrenciler.index');
        Route::get('/ogrenciler/{ogrenci}', [AdminOgrenciController::class, 'show'])->name('ogrenciler.show');
    });
    /**
     * HOCA
     */
    Route::prefix('hoca')->name('hoca.')->middleware('rol:hoca')->group(function () {


        // 1) Yoklama giriş: Ders açılımları listesi + oturum başlat
        Route::get('/yoklama', [HocaYoklamaController::class, 'start'])->name('yoklama.start');
        Route::post('/yoklama/oturum-baslat', [HocaYoklamaController::class, 'oturumBaslat'])->name('yoklama.oturumBaslat');

        // 2) Oturum ekranı (QR + katılım listesi burada)
        Route::get('/yoklama/oturum/{oturum}', [HocaYoklamaController::class, 'show'])->name('yoklama.show');

        // 3) Pencere aç/kapat + canlı qr + canlı katılımlar
        Route::post('/yoklama/oturum/{oturum}/pencere-ac', [HocaYoklamaController::class, 'pencereAc'])->name('yoklama.pencereAc');
        Route::post('/yoklama/pencere/{pencere}/kapat', [HocaYoklamaController::class, 'pencereKapat'])->name('yoklama.pencereKapat');

        Route::get('/yoklama/pencere/{pencere}/qr', [HocaYoklamaController::class, 'qr'])->name('yoklama.qr');
        Route::get('/yoklama/oturum/{oturum}/katilimlar', [HocaYoklamaController::class, 'katilimlar'])->name('yoklama.katilimlar');


        Route::get('/panel', HocaPanelController::class)->name('panel');

        Route::resource('/dersler', DersController::class)->names('dersler');

        Route::resource('/ders-acilimlari', DersAcilimController::class)
            ->names('ders_acilimlari')
            ->parameters(['ders-acilimlari' => 'dersAcilim']);

        // Açılış detay işlemleri
        Route::post('ders-acilimlari/{acilim}/ogrenci-ekle', [HocaKayitController::class, 'ogrenciEkle'])
            ->name('ders_acilimlari.ogrenci_ekle');

        Route::delete('ders-acilimlari/{acilim}/ogrenci/{ogrenci}', [HocaKayitController::class, 'kayitSil'])
            ->name('ders_acilimlari.ogrenci_sil');

        Route::post('ders-acilimlari/{acilim}/davet-olustur', [HocaDavetController::class, 'olustur'])
            ->name('ders_acilimlari.davet_olustur');

        Route::patch('ders-acilimlari/{acilim}/davet/{davet}/pasif', [HocaDavetController::class, 'pasifEt'])
            ->name('ders_acilimlari.davet_pasif');
    });

    /**
     * OGRENCI
     */
    Route::prefix('ogrenci')->name('ogrenci.')->middleware('rol:ogrenci')->group(function () {

        Route::get('/panel', OgrenciPanelController::class)->name('panel');

        Route::get('/yoklama', [OgrenciYoklamaController::class, 'auto'])->name('yoklama.auto');


        Route::get('/derslerim', [OgrenciDersController::class, 'derslerim'])->name('derslerim.index');
        Route::get('/derslerim/{ders_kayit}/show', [OgrenciDersController::class, 'show'])->name('derslerim.show');


        Route::get('/yoklamalarim', [OgrenciPanelController::class, 'yoklamalarim'])->name('yoklamalarim');


        // öğrenci kendi kayıt olur
        Route::post('ders-acilimlari/{acilim}/kayit', [OgrenciKayitController::class, 'kayit'])->name('ders_kayit');
    });

    Route::middleware('rol:ogrenci,admin')->group(function () {
        Route::get('davet/{token}', [DavetController::class, 'goster'])->name('davet.goster');
        Route::post('davet/{token}/kabul', [DavetController::class, 'kabul'])->name('davet.kabul');
    });
});
