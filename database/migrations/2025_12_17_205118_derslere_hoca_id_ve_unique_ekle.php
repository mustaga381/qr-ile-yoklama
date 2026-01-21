<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1) Kolonu nullable ekle (eski satırlara 0 basmasın)
        Schema::table('dersler', function (Blueprint $table) {
            if (!Schema::hasColumn('dersler', 'hoca_id')) {
                $table->unsignedBigInteger('hoca_id')->nullable()->after('id');
                $table->index('hoca_id');
            }
        });

        // 2) Mevcut satırları bir admin/hoca ile doldur
        $fallbackId = DB::table('kullanicilar')
            ->whereIn('rol', ['admin', 'hoca'])
            ->orderBy('id')
            ->value('id');

        if ($fallbackId) {
            DB::table('dersler')
                ->whereNull('hoca_id')
                ->orWhere('hoca_id', 0)
                ->update(['hoca_id' => $fallbackId]);
        }

        // 3) FK ekle (artık 0/NULL problemi yok)
        Schema::table('dersler', function (Blueprint $table) {
            // eski unique varsa kaldır (adı farklı olabilir)
            try {
                $table->dropUnique('dersler_ders_kodu_unique');
            } catch (\Throwable $e) {
            }

            // FK
            $table->foreign('hoca_id')
                ->references('id')->on('kullanicilar')
                ->cascadeOnDelete();

            // ✅ Yeni unique: aynı hoca aynı ders_kodu’nu 2 kere ekleyemez
            $table->unique(['hoca_id', 'ders_kodu'], 'uniq_hoca_ders_kodu');
        });
    }

    public function down(): void
    {
        Schema::table('dersler', function (Blueprint $table) {
            try {
                $table->dropUnique('uniq_hoca_ders_kodu');
            } catch (\Throwable $e) {
            }
            try {
                $table->dropForeign(['hoca_id']);
            } catch (\Throwable $e) {
            }
            try {
                $table->dropIndex(['hoca_id']);
            } catch (\Throwable $e) {
            }
            if (Schema::hasColumn('dersler', 'hoca_id')) {
                $table->dropColumn('hoca_id');
            }
        });
    }
};
