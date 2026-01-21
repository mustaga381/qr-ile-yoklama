<?php

// database/migrations/xxxx_xx_xx_xxxxxx_bolumler_tablosu_olustur.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bolumler', function (Blueprint $table) {
            $table->id();
            $table->string('bolum_adi', 150);
            $table->boolean('aktif_mi')->default(true);
            $table->timestamps();

            $table->unique('bolum_adi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bolumler');
    }
};
