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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_id')->constrained('card_articles')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->text('catatan')->nullable();
            $table->string('image');
            $table->enum('status', ['Tertunda', 'Disetujui', 'Ditolak'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
