<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendapatan extends Model
{
    use HasFactory;
    protected $table = 'pendapatan';
    protected $fillable = [
        'kategori', 'jumlah', 'satuan', 'harga_per_satuan',
        'total_pendapatan', 'tanggal_transaksi', 'nama_pembeli', 'nama_perusahaan'
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($pendapatan) {
            $pendapatan->total_pendapatan = $pendapatan->jumlah * $pendapatan->harga_per_satuan;
        });

        static::updating(function ($pendapatan) {
            $pendapatan->total_pendapatan = $pendapatan->jumlah * $pendapatan->harga_per_satuan;
        });
    }
}
