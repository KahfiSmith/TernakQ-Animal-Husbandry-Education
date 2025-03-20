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
        Schema::create('pengeluaran', function (Blueprint $table) {
            $table->id();
            $table->enum('category', ['Pembelian Ayam', 'Pakan Ayam', 'Obat, Vitamin, Vaksin']);
            $table->text('description')->nullable();
            $table->integer('jumlah')->unsigned();
            $table->enum('satuan', ['ekor', 'kg', 'karung', 'botol', 'unit', 'paket']); // ENUM untuk satuan
            $table->decimal('harga_per_satuan');
            $table->decimal('total_biaya');
            $table->string('supplier')->nullable();
            $table->date('tanggal_pembelian');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengeluaran');
    }
};
