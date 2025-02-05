<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kandang_ayam', function (Blueprint $table) { 
            $table->id();
            $table->string('nama_kandang');
            $table->integer('kapasitas');
            $table->enum('status_kandang', ['Aktif', 'Tidak Aktif'])->default('Aktif');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('kandang_ayam');
    }

    public function populasiAyam()
    {
        return $this->hasMany(PopulasiAyam::class, 'kandang_id');
    }

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::deleting(function ($kandang) {
    //         $kandang->populasiAyam()->each(function ($populasi) {
    //             $populasi->delete();
    //         });
    //     });
    // }
};
