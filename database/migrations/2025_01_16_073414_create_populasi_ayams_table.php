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
        Schema::create('populasi_ayam', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('kandang_id')->nullable()->constrained('kandang_ayam')->onDelete('cascade');
            $table->string('kode_batch')->unique();
            $table->string('nama_batch');
            $table->date('tanggal_doc');
            $table->integer('jumlah_ayam_masuk');
            $table->enum('status_ayam', ['Proses', 'Siap Panen', 'Sudah Panen'])->default('Proses');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('populasi_ayam');
    }
};
