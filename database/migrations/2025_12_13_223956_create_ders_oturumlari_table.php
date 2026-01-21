<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ders_oturumlari', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ders_acilim_id')
                ->constrained('ders_acilimlari')
                ->cascadeOnDelete();

            $table->date('oturum_tarihi')->index();
            $table->dateTime('baslangic_zamani')->nullable();
            $table->dateTime('bitis_zamani')->nullable();
            $table->string('baslik')->nullable();

            $table->foreignId('hoca_id')
                ->nullable()
                ->constrained('kullanicilar')
                ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['ders_acilim_id', 'oturum_tarihi'], 'oturum_arama_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ders_oturumlari');
    }
};
