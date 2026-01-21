<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('yoklamalar', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ders_oturum_id')
                ->constrained('ders_oturumlari')
                ->cascadeOnDelete();

            $table->foreignId('ogrenci_id')
                ->constrained('kullanicilar')
                ->cascadeOnDelete();

            $table->foreignId('yoklama_pencere_id')
                ->nullable()
                ->constrained('yoklama_pencereleri')
                ->nullOnDelete();

            $table->foreignId('cihaz_id')
                ->nullable()
                ->constrained('cihazlar')
                ->nullOnDelete();

            $table->string('ip_adresi', 45)->nullable(); // IPv4/IPv6
            $table->text('user_agent')->nullable();

            $table->enum('durum', ['katildi', 'gec_kaldi', 'manuel', 'supheli', 'reddedildi'])
                ->default('katildi')
                ->index();

            $table->dateTime('isaretlenme_zamani')->index();
            $table->unsignedInteger('qr_adim_no')->nullable();

            $table->timestamps();

            $table->unique(['ders_oturum_id', 'ogrenci_id'], 'oturum_ogrenci_uniq');
            $table->index(['ders_oturum_id', 'durum'], 'yoklama_oturum_durum_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('yoklamalar');
    }
};
