<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('ders_acilimlari', function (Blueprint $table) {
            if (Schema::hasColumn('ders_acilimlari', 'kayit_modu')) {
                $table->dropColumn('kayit_modu');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ders_acilimlari', function (Blueprint $table) {
            $table->string('kayit_modu', 20)->default('acik');
        });
    }
};
