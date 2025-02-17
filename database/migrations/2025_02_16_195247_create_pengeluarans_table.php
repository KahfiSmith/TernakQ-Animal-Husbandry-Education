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
            $table->enum('category', ['Pembelian Ayam', 'Pakan Ayam', 'Obat, Vitamin, Vaksin', 'Listrik, Air, Peralatan']);
            $table->text('description');
            $table->decimal('jumlah', 10, 2)->nullable();
            $table->string('satuan', 50)->nullable();
            $table->decimal('harga_per_satuan', 10, 2)->nullable();
            $table->decimal('total_biaya', 15, 2);
            $table->string('supplier')->nullable(); 
            $table->date('tanggal_pembelian');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluaran');
    }
};
