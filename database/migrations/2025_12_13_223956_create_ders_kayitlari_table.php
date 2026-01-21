<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ders_kayitlari', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ders_acilim_id')
                ->constrained('ders_acilimlari')
                ->cascadeOnDelete();

            $table->foreignId('ogrenci_id')
                ->constrained('kullanicilar')
                ->cascadeOnDelete();

            $table->enum('durum', ['aktif', 'birakti'])->default('aktif')->index();
            $table->timestamp('kayit_tarihi')->useCurrent();

            $table->timestamps();

            $table->unique(['ders_acilim_id', 'ogrenci_id'], 'ders_kayit_uniq');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ders_kayitlari');
    }
};
