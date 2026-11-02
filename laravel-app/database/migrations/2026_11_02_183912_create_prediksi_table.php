<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prediksi', function (Blueprint $table) {
            $table->id();

            $table->foreignId('kelurahan_id')
                ->constrained('kelurahan')
                ->cascadeOnDelete();

            $table->foreignId('status_prediksi_id')
                ->constrained('status_prediksi')
                ->cascadeOnDelete();

            $table->date('tanggal');

            $table->decimal('probabilitas', 6, 5);

            $table->string('tingkat')->default('rendah');

            $table->boolean('is_banjir')->default(false);

            $table->timestamps();

            $table->unique(['kelurahan_id', 'tanggal']);
            $table->index('tanggal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prediksi');
    }
};