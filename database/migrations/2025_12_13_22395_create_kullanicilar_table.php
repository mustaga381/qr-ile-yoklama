<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kullanicilar', function (Blueprint $table) {
            $table->id();

            $table->string('ad_soyad');
            $table->string('eposta')->unique();
            $table->string('sifre');

            $table->enum('rol', ['ogrenci', 'hoca', 'admin'])->default('ogrenci')->index();

            $table->string('ogrenci_no', 32)->nullable()->unique();
            $table->string('personel_no', 32)->nullable()->unique();

            $table->boolean('aktif_mi')->default(true);

            $table->timestamp('eposta_dogrulandi_at')->nullable();
            $table->string('hatirla_token', 100)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kullanicilar');
    }
};
