<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KandangAyam extends Model
{
    use HasFactory;

    protected $table = 'kandang_ayam';

    protected $fillable = [
        'nama_kandang',
        'kapasitas',
        'status_kandang',
        'user_id',
    ];

    const STATUS_AKTIF = 'Aktif';
    const STATUS_TIDAK_AKTIF = 'Tidak Aktif';

    public static function getStatusOptions()
    {
        return [self::STATUS_AKTIF, self::STATUS_TIDAK_AKTIF];
    }

    public function populasiAyam()
    {
        return $this->hasMany(PopulasiAyam::class, 'kandang_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
