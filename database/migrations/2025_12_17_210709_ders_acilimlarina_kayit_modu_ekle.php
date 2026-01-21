<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ders_acilimlari', function (Blueprint $table) {
            $table->string('kayit_modu', 20)->default('acik'); // acik|onayli|davet
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ders_acilimlari', function (Blueprint $table) {
            //
        });
    }
};
