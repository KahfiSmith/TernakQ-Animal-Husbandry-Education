<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenggunaanPakan extends Model
{
    protected $table = 'penggunaan_pakan';
    use HasFactory;

    protected $fillable = [
        'pakan_id', 'tanggal_pakai', 'jumlah_pakai'
    ];

    // Relasi dengan Pakan
    public function pakan()
    {
        return $this->belongsTo(Pakan::class, 'pakan_id');
    }
}
