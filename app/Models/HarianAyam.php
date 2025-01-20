<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HarianAyam extends Model
{
    use HasFactory;

    protected $table = 'harian_ayam';

    protected $fillable = [
        'id_populasi',
        'nama_batch',
        'tanggal_input',
        'jumlah_ayam_mati',
        'jumlah_ayam_sakit',
    ];

    public function populasiAyam()
    {
        return $this->belongsTo(PopulasiAyam::class, 'id_populasi');
    }

    protected static function booted()
    {
        static::creating(function ($harian) {
            $harian->nama_batch = $harian->populasiAyam->nama_batch;
        });
    }
}
