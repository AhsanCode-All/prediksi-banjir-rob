<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelurahan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_wilayah')->unique();
            $table->string('nama_kelurahan');
            $table->string('kecamatan')->nullable();
            $table->decimal('elevasi', 8, 2)->nullable();
            $table->decimal('jarak_pantai', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelurahan');
    }
};