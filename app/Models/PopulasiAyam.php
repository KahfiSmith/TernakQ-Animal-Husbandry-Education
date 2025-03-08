<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class PopulasiAyam extends Model
{
    use HasFactory;

    protected $table = 'populasi_ayam';

    protected $fillable = [
        'kandang_id',
        'kode_batch',
        'nama_batch',
        'tanggal_doc',
        'jumlah_ayam_masuk',
        'status_ayam',
        'user_id',
    ];

    public function harianAyam()
    {
        return $this->hasMany(HarianAyam::class, 'id_populasi');
    }

    public function kandang()
    {
        return $this->belongsTo(KandangAyam::class, 'kandang_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
