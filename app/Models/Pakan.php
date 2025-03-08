<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pakan extends Model
{
    use HasFactory;
    
    protected $table = 'pakan';
    protected $fillable = [
        'nama_pakan', 
        'jenis_pakan', 
        'berat', 
        'tanggal_masuk', 
        'harga_per_kg',
        'user_id', 
    ];

    // Relasi dengan PenggunaanPakan
    public function penggunaan()
    {
        return $this->hasMany(PenggunaanPakan::class, 'pakan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
