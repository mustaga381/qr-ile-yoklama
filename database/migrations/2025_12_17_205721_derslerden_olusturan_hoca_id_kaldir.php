<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('dersler', function (Blueprint $table) {
            if (Schema::hasColumn('dersler', 'hoca_id')) {
                // FK varsa önce düşür
                try {
                    $table->dropForeign(['hoca_id']);
                } catch (\Throwable $e) {
                }
                try {
                    $table->dropIndex(['hoca_id']);
                } catch (\Throwable $e) {
                }
                $table->dropColumn('hoca_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('dersler', function (Blueprint $table) {
            $table->unsignedBigInteger('hoca_id')->nullable();
        });
    }
};
