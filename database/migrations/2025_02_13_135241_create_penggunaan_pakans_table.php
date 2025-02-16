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
        Schema::create('penggunaan_pakan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pakan_id')->constrained('pakan')->onDelete('cascade');
            $table->date('tanggal_pakai');
            $table->decimal('jumlah_pakai', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penggunaan_pakan');
    }
};
