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
        Schema::create('ders_davetleri', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ders_acilim_id');
            $table->unsignedBigInteger('olusturan_id');

            $table->string('token', 80)->unique();

            $table->unsignedBigInteger('hedef_ogrenci_id')->nullable();
            $table->string('hedef_eposta')->nullable();

            $table->dateTime('son_gecerlilik_tarihi')->nullable();
            $table->unsignedInteger('max_kullanim')->default(1);
            $table->unsignedInteger('kullanim_sayisi')->default(0);
            $table->boolean('aktif')->default(true);

            $table->timestamps();

            $table->foreign('ders_acilim_id')->references('id')->on('ders_acilimlari')->cascadeOnDelete();
            $table->foreign('olusturan_id')->references('id')->on('kullanicilar')->cascadeOnDelete();
            $table->foreign('hedef_ogrenci_id')->references('id')->on('kullanicilar')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ders_davetleri');
    }
};
