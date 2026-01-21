<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('yoklama_supheleri', function (Blueprint $table) {
            $table->id();

            $table->foreignId('yoklama_id')
                ->constrained('yoklamalar')
                ->cascadeOnDelete();

            $table->enum('tur', [
                'yeni_cihaz',
                'ayni_oturum_coklu_cihaz',
                'ip_uyusmazligi',
                'qr_tekrar',
            ])->index();

            $table->json('meta')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('yoklama_supheleri');
    }
};
