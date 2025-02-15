<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PenggunaanPakanSeeder extends Seeder
{
    
    protected $fillable = [
        'nama_pakan', 'jenis_pakan', 'berat', 'tanggal_masuk', 'tanggal_kadaluarsa', 'harga_per_kg'
    ];

    // Relasi dengan PenggunaanPakan
    public function penggunaan()
    {
        return $this->hasMany(PenggunaanPakan::class, 'pakan_id');
    }
}
