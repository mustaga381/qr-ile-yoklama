<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ders_acilimlari', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ders_id')
                ->constrained('dersler')
                ->cascadeOnDelete();

            $table->foreignId('hoca_id')
                ->nullable()
                ->constrained('kullanicilar')
                ->nullOnDelete();

            $table->string('akademik_yil', 9)->index(); // 2025-2026
            $table->enum('donem', ['guz', 'bahar', 'yaz'])->index();
            $table->string('sube', 20)->default('1');

            $table->boolean('aktif_mi')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['ders_id', 'akademik_yil', 'donem', 'sube'], 'ders_acilim_uniq');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ders_acilimlari');
    }
};
