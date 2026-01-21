<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cihazlar', function (Blueprint $table) {
            $table->id();

            $table->foreignId('kullanici_id')
                ->constrained('kullanicilar')
                ->cascadeOnDelete();

            $table->string('cihaz_uuid', 64)->unique();
            $table->string('etiket')->nullable();

            $table->string('platform')->nullable();
            $table->text('user_agent')->nullable();

            $table->dateTime('ilk_gorulme')->nullable();
            $table->dateTime('son_gorulme')->nullable();

            $table->boolean('guvenilir_mi')->default(false)->index();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cihazlar');
    }
};
