<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('status_prediksi', function (Blueprint $table) {
            $table->id();

            $table->date('tanggal')->unique();

            $table->string('status')->default('menunggu');

            $table->string('model_versi')->nullable();

            $table->boolean('is_simulasi')->default(false);

            $table->unsignedInteger('total_data')->default(0);

            $table->text('pesan')->nullable();

            $table->timestamp('mulai_diproses')->nullable();

            $table->timestamp('selesai_diproses')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('status_prediksi');
    }
};