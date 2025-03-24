<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\KandangAyam;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
    public function index(Request $request)
{
    $userId = Auth::id();  // Ambil ID user yang sedang login

    // Total Kandang Ayam Aktif berdasarkan user_id
    $totalKandangAyams = KandangAyam::where('status_kandang', 'Aktif')
                                     ->where('user_id', $userId)  // Filter berdasarkan user_id
                                     ->count();

    // Total kapasitas kandang ayam aktif berdasarkan user_id
    $totalCapacity = KandangAyam::where('status_kandang', 'Aktif')
                                ->where('user_id', $userId)  // Filter berdasarkan user_id
                                ->sum('kapasitas');

    // Total ayam mati (mengambil data dari tabel harian_ayam) berdasarkan user_id
    $totalDeathsThisMonth = DB::table('harian_ayam')
        ->whereMonth('tanggal_input', Carbon::now()->month)
        ->whereYear('tanggal_input', Carbon::now()->year)
        ->whereIn('id_populasi', function ($query) use ($userId) {
            $query->select('id')->from('populasi_ayam')->where('user_id', $userId);
        })
        ->sum('jumlah_ayam_mati');

    // Hitung kematian ayam pada bulan berjalan dan bulan sebelumnya berdasarkan tanggal_input
    $currentMonthDeaths = DB::table('harian_ayam')
        ->whereYear('tanggal_input', Carbon::now()->year)
        ->whereMonth('tanggal_input', Carbon::now()->month)
        ->whereIn('id_populasi', function ($query) use ($userId) {
            $query->select('id')->from('populasi_ayam')->where('user_id', $userId);
        })
        ->sum('jumlah_ayam_mati');

    $previousMonthDeaths = DB::table('harian_ayam')
        ->whereYear('tanggal_input', Carbon::now()->year)
        ->whereMonth('tanggal_input', Carbon::now()->subMonth()->month)
        ->whereIn('id_populasi', function ($query) use ($userId) {
            $query->select('id')->from('populasi_ayam')->where('user_id', $userId);
        })
        ->sum('jumlah_ayam_mati');

    // Hitung persentase perubahan (jika data bulan sebelumnya tidak nol)
    $percentageChange = 0;
    if ($previousMonthDeaths > 0) {
        $percentageChange = (($currentMonthDeaths - $previousMonthDeaths) / $previousMonthDeaths) * 100;
    }

    // Data Chart Bulanan dari harian_ayam untuk ayam sakit dan mati
    // Data detail kandang untuk tabel manajemen ayam, gabungkan data dari populasi_ayam dan harian_ayam
    $populasiSub = DB::table('populasi_ayam')
        ->whereIn('status_ayam', ['Proses', 'Siap Panen'])
        ->where('user_id', $userId)  // Filter berdasarkan user_id
        ->select('kandang_id', DB::raw('SUM(jumlah_ayam_masuk) as total_ayam'))
        ->groupBy('kandang_id');

    $harianSub = DB::table('harian_ayam')
        ->join('populasi_ayam', 'harian_ayam.id_populasi', '=', 'populasi_ayam.id')
        ->whereIn('populasi_ayam.status_ayam', ['Proses', 'Siap Panen'])
        ->where('populasi_ayam.user_id', $userId)  // Filter berdasarkan user_id
        ->select('populasi_ayam.kandang_id', 
                DB::raw('SUM(jumlah_ayam_sakit) as total_sick'),
                DB::raw('SUM(jumlah_ayam_mati) as total_dead'))
        ->groupBy('populasi_ayam.kandang_id');

    $KandangAyams = DB::table('kandang_ayam')
        ->where('user_id', $userId)  // Filter berdasarkan user_id
        ->leftJoinSub($populasiSub, 'populasi', function ($join) {
            $join->on('kandang_ayam.id', '=', 'populasi.kandang_id');
        })
        ->leftJoinSub($harianSub, 'harian', function ($join) {
            $join->on('kandang_ayam.id', '=', 'harian.kandang_id');
        })
        ->select(
            'kandang_ayam.*',
            DB::raw('COALESCE(populasi.total_ayam, 0) as total_ayam'),
            DB::raw('COALESCE(harian.total_sick, 0) as total_sick'),
            DB::raw('COALESCE(harian.total_dead, 0) as total_dead')
        )
        ->having('total_ayam', '>', 0)
        ->paginate(5);

    // Pendapatan bulan ini
    $pendapatanBulanIni = DB::table('pendapatan')
        ->whereMonth('tanggal_transaksi', Carbon::now()->month)
        ->whereYear('tanggal_transaksi', Carbon::now()->year)
        ->where('user_id', $userId)  // Filter berdasarkan user_id
        ->sum('total_pendapatan');

    // Pengeluaran bulan ini
    $pengeluaranBulanIni = DB::table('pengeluaran')
        ->whereMonth('tanggal_pembelian', Carbon::now()->month)
        ->whereYear('tanggal_pembelian', Carbon::now()->year)
        ->where('user_id', $userId)  // Filter berdasarkan user_id
        ->sum('total_biaya');

    return view('dashboard', compact(
        'totalKandangAyams',
        'totalCapacity',
        'totalDeathsThisMonth',
        'percentageChange',
        'KandangAyams',
        'pendapatanBulanIni',
        'pengeluaranBulanIni',
    ));
}

}
