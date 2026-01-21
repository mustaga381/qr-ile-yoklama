<?php

// database/migrations/xxxx_xx_xx_xxxxxx_kullanicilara_bolum_id_ekle.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('kullanicilar', function (Blueprint $table) {
            // mevcut kayıtlar varsa patlamasın diye önce nullable
            $table->foreignId('bolum_id')->nullable()->after('rol')->constrained('bolumler')->nullOnDelete();
            $table->index('bolum_id');
        });
    }

    public function down(): void
    {
        Schema::table('kullanicilar', function (Blueprint $table) {
            $table->dropConstrainedForeignId('bolum_id');
        });
    }
};
