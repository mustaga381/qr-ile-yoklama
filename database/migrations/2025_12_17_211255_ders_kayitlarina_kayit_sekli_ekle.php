<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('ders_kayitlari', function (Blueprint $table) {
            if (!Schema::hasColumn('ders_kayitlari', 'kayit_sekli')) {
                $table->string('kayit_sekli', 20)->default('ogrenci')->after('durum');
                // ogrenci | hoca | davet
            }
        });

        // Eğer eski kolonun adı "kaynak" ise veriyi taşı
        if (Schema::hasColumn('ders_kayitlari', 'kaynak')) {
            DB::table('ders_kayitlari')->update([
                'kayit_sekli' => DB::raw("COALESCE(kaynak,'ogrenci')")
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('ders_kayitlari', function (Blueprint $table) {
            if (Schema::hasColumn('ders_kayitlari', 'kayit_sekli')) {
                $table->dropColumn('kayit_sekli');
            }
        });
    }
};
