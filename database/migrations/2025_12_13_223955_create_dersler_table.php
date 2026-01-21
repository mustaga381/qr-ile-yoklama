<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dersler', function (Blueprint $table) {
            $table->id();

            $table->string('ders_kodu', 50)->unique();
            $table->string('ders_adi');
            $table->text('aciklama')->nullable();

            $table->foreignId('hoca_id')
                ->nullable()
                ->constrained('kullanicilar')
                ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dersler');
    }
};
