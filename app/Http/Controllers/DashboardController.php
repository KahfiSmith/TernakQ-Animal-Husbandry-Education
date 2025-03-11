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
        $totalKandangAyams = KandangAyam::where('status_kandang', 'Aktif')->count();
        $totalCapacity = KandangAyam::where('status_kandang', 'Aktif')->sum('kapasitas');

        // Total ayam mati (mengambil data dari tabel harian_ayam)
        $totalDeaths = DB::table('harian_ayam')->sum('jumlah_ayam_mati');

        // Hitung kematian ayam pada bulan berjalan dan bulan sebelumnya berdasarkan tanggal_input
        $currentMonthDeaths = DB::table('harian_ayam')
            ->whereYear('tanggal_input', Carbon::now()->year)
            ->whereMonth('tanggal_input', Carbon::now()->month)
            ->sum('jumlah_ayam_mati');

        $previousMonthDeaths = DB::table('harian_ayam')
            ->whereYear('tanggal_input', Carbon::now()->year)
            ->whereMonth('tanggal_input', Carbon::now()->subMonth()->month)
            ->sum('jumlah_ayam_mati');

        // Hitung persentase perubahan (jika data bulan sebelumnya tidak nol)
        $percentageChange = 0;
        if ($previousMonthDeaths > 0) {
            $percentageChange = (($currentMonthDeaths - $previousMonthDeaths) / $previousMonthDeaths) * 100;
        }

        // Data Chart Bulanan dari harian_ayam untuk ayam sakit dan mati
        $monthlyData = DB::table('harian_ayam')
    ->join('populasi_ayam', 'harian_ayam.id_populasi', '=', 'populasi_ayam.id')
    ->whereIn('populasi_ayam.status_ayam', ['Proses', 'Siap Panen'])
    ->select(
        DB::raw('MONTH(harian_ayam.tanggal_input) as month'),
        DB::raw('SUM(harian_ayam.jumlah_ayam_sakit) as sick'),
        DB::raw('SUM(harian_ayam.jumlah_ayam_mati) as dead')
    )
    ->whereYear('harian_ayam.tanggal_input', Carbon::now()->year)
    ->groupBy(DB::raw('MONTH(harian_ayam.tanggal_input)'))
    ->orderBy('month')
    ->get();

// Data Chart Harian (untuk hari ini)
$todayData = DB::table('harian_ayam')
    ->join('populasi_ayam', 'harian_ayam.id_populasi', '=', 'populasi_ayam.id')
    ->whereIn('populasi_ayam.status_ayam', ['Proses', 'Siap Panen'])
    ->whereDate('harian_ayam.tanggal_input', Carbon::today())
    ->select(
        DB::raw('SUM(harian_ayam.jumlah_ayam_sakit) as sick'),
        DB::raw('SUM(harian_ayam.jumlah_ayam_mati) as dead')
    )
    ->first();

        // Data detail kandang untuk tabel manajemen ayam, gabungkan data dari populasi_ayam dan harian_ayam
        $populasiSub = DB::table('populasi_ayam')
        ->whereIn('status_ayam', ['Proses', 'Siap Panen'])
        ->select('kandang_id', DB::raw('SUM(jumlah_ayam_masuk) as total_ayam'))
        ->groupBy('kandang_id');

        $harianSub = DB::table('harian_ayam')
            ->join('populasi_ayam', 'harian_ayam.id_populasi', '=', 'populasi_ayam.id')
            ->whereIn('populasi_ayam.status_ayam', ['Proses', 'Siap Panen'])
            ->select('populasi_ayam.kandang_id', 
                    DB::raw('SUM(jumlah_ayam_sakit) as total_sick'),
                    DB::raw('SUM(jumlah_ayam_mati) as total_dead'))
            ->groupBy('populasi_ayam.kandang_id');

        $KandangAyams = DB::table('kandang_ayam')
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
            ->get();

            // Pendapatan bulan ini
$pendapatanBulanIni = DB::table('pendapatan')
->whereMonth('tanggal_transaksi', Carbon::now()->month)
->whereYear('tanggal_transaksi', Carbon::now()->year)
->sum('total_pendapatan');

// Pengeluaran bulan ini
$pengeluaranBulanIni = DB::table('pengeluaran')
->whereMonth('tanggal_pembelian', Carbon::now()->month)
->whereYear('tanggal_pembelian', Carbon::now()->year)
->sum('total_biaya');


        return view('dashboard', compact(
            'totalKandangAyams',
            'totalCapacity',
            'totalDeaths',
            'percentageChange',
            'monthlyData',
            'todayData',
            'KandangAyams',
            'pendapatanBulanIni',
            'pengeluaranBulanIni',
        ));
    }
}
