<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;
    protected $table = 'pengeluaran';
    protected $fillable = [
        'category',
        'description',
        'jumlah',
        'satuan',
        'harga_per_satuan',
        'total_biaya',
        'supplier',
        'tanggal_pembelian',
        'user_id',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($pengeluaran) {
            $pengeluaran->total_biaya = $pengeluaran->jumlah * $pengeluaran->harga_per_satuan;
        });

        static::updating(function ($pengeluaran) {
            $pengeluaran->total_biaya = $pengeluaran->jumlah * $pengeluaran->harga_per_satuan;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
