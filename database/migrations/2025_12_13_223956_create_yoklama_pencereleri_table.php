<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('yoklama_pencereleri', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ders_oturum_id')
                ->constrained('ders_oturumlari')
                ->cascadeOnDelete();

            $table->foreignId('acani_hoca_id')
                ->nullable()
                ->constrained('kullanicilar')
                ->nullOnDelete();

            $table->dateTime('baslangic_zamani');
            $table->dateTime('bitis_zamani');

            $table->unsignedTinyInteger('yenileme_saniye')->default(10); // 5/10
            $table->string('gizli_anahtar', 128);

            $table->enum('durum', ['acik', 'kapali', 'sure_doldu'])->default('acik')->index();

            $table->timestamps();

            $table->index(['ders_oturum_id', 'durum'], 'pencere_oturum_durum_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('yoklama_pencereleri');
    }
};
