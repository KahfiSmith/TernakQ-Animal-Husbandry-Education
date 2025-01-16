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
        Schema::create('harian_ayam', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_populasi');
            $table->string('nama_batch');
            $table->date('tanggal_input');
            $table->integer('jumlah_ayam_mati');
            $table->integer('jumlah_ayam_sakit');
            $table->timestamps();

            $table->foreign('id_populasi')->references('id')->on('populasi_ayam')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('harian_ayams');
    }
};
