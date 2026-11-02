<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_harian', function (Blueprint $table) {
            $table->id();

            $table->foreignId('kelurahan_id')
                ->constrained('kelurahan')
                ->cascadeOnDelete();

            $table->date('tanggal');

            $table->decimal('curah_hujan', 10, 2)->nullable();
            $table->decimal('pasang_tertinggi', 10, 2)->nullable();
            $table->decimal('gelombang_tertinggi', 10, 2)->nullable();

            $table->foreignId('impor_id')
                ->nullable()
                ->constrained('impor')
                ->nullOnDelete();

            $table->timestamps();

            $table->unique(['kelurahan_id', 'tanggal']);
            $table->index('tanggal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_harian');
    }
};