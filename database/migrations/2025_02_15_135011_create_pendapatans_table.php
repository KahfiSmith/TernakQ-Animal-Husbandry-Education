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
        Schema::create('pendapatan', function (Blueprint $table) {
            $table->id();
            $table->enum('kategori', ['Penjualan Ayam', 'Penjualan Kotoran', 'Kemitraan'])->index();
            $table->integer('jumlah')->unsigned();
            $table->enum('satuan', ['ekor', 'kg', 'karung']);
            $table->decimal('harga_per_satuan');
            $table->decimal('total_pendapatan');
            $table->date('tanggal_transaksi');
            $table->string('nama_pembeli')->nullable();
            $table->string('nama_perusahaan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendapatan');
    }
};
