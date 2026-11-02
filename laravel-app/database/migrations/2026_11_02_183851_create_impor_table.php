<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('impor', function (Blueprint $table) {
            $table->id();
            $table->string('nama_file');
            $table->string('path_file')->nullable();
            $table->unsignedInteger('total_data')->default(0);
            $table->unsignedInteger('data_berhasil')->default(0);
            $table->unsignedInteger('data_gagal')->default(0);
            $table->string('status')->default('diproses');
            $table->text('keterangan')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('impor');
    }
};