<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendapatan;
use App\Models\Pengeluaran;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class KeuanganController extends Controller
{
    /**
     * Menampilkan laporan keuangan berdasarkan bulan dan tahun tertentu.
     */
    public function indexKeuangan(Request $request)
{
    try {
        // Ambil bulan & tahun dari request, default ke bulan & tahun saat ini
        $bulan = $request->get('bulan', Carbon::now()->format('m'));
        $tahun = $request->get('tahun', Carbon::now()->format('Y'));

        // Jika tahun yang dipilih adalah tahun sekarang, validasi bulan agar tidak lebih dari bulan sekarang
        if ($tahun == Carbon::now()->format('Y') && $bulan > Carbon::now()->format('m')) {
            $bulan = Carbon::now()->format('m'); // Set bulan ke bulan saat ini
        }

        // Validasi tahun tidak lebih dari tahun saat ini
        if ($tahun > Carbon::now()->format('Y')) {
            $tahun = Carbon::now()->format('Y'); // Set tahun ke tahun saat ini
        }

        // Konversi ke format nama bulan
        $namaBulan = Carbon::create()->month($bulan)->translatedFormat('F');

        // Ambil data pendapatan pada bulan & tahun tertentu
        $pendapatan = Pendapatan::where('user_id', Auth::id())
            ->whereYear('tanggal_transaksi', $tahun)
            ->whereMonth('tanggal_transaksi', $bulan)
            ->orderBy('tanggal_transaksi', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'tanggal' => $item->tanggal_transaksi,
                    'keterangan' => $item->kategori,
                    'jumlah' => $item->jumlah * $item->harga_per_satuan,
                    'tipe' => 'pendapatan',
                ];
            });

        // Ambil data pengeluaran pada bulan & tahun tertentu
        $pengeluaran = Pengeluaran::where('user_id', Auth::id())
            ->whereYear('tanggal_pembelian', $tahun)
            ->whereMonth('tanggal_pembelian', $bulan)
            ->orderBy('tanggal_pembelian', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'tanggal' => $item->tanggal_pembelian,
                    'keterangan' => $item->category . ' - ' . $item->description,
                    'jumlah' => $item->jumlah * $item->harga_per_satuan,
                    'tipe' => 'pengeluaran',
                ];
            });

        // Gabungkan data pendapatan dan pengeluaran
        $transaksi = collect($pendapatan)->merge($pengeluaran)->sortBy('tanggal');

        // Hitung total pendapatan, total pengeluaran, dan laba bersih
        $totalPendapatan = $pendapatan->sum('jumlah');
        $totalPengeluaran = $pengeluaran->sum('jumlah');
        $labaBersih = $totalPendapatan - $totalPengeluaran;

        // Perbaikan penggunaan filter() untuk menghitung total harian
        $totalPendapatanHarian = $transaksi->filter(fn($trx) => $trx['tipe'] === 'pendapatan')->sum('jumlah');
        $totalPengeluaranHarian = $transaksi->filter(fn($trx) => $trx['tipe'] === 'pengeluaran')->sum('jumlah');
        $totalSaldoHarian = $totalPendapatanHarian - $totalPengeluaranHarian;

        return view('finance-management', compact(
            'transaksi', 
            'totalPendapatan', 
            'totalPengeluaran', 
            'labaBersih', 
            'bulan', 
            'tahun', 
            'namaBulan',
            'totalPendapatanHarian',
            'totalPengeluaranHarian',
            'totalSaldoHarian'
        ));
    } catch (\Exception $e) {
        Log::error('Gagal memuat laporan keuangan: ' . $e->getMessage());

        return redirect()->route('finance-management')->with([
            'status' => 'error',
            'message' => 'Terjadi kesalahan saat memuat laporan keuangan.',
        ]);
    }
}



    public function exportPDF(Request $request)
    {
        $bulan = $request->get('bulan', Carbon::now()->format('m'));
        $tahun = $request->get('tahun', Carbon::now()->format('Y'));
        $namaBulan = Carbon::create()->month($bulan)->translatedFormat('F');

        // Ambil data keuangan
        $pendapatan = Pendapatan::where('user_id', Auth::id())
            ->whereYear('tanggal_transaksi', $tahun)
            ->whereMonth('tanggal_transaksi', $bulan)
            ->orderBy('tanggal_transaksi', 'asc')
            ->get()
            ->map(fn($item) => [
                'tanggal' => $item->tanggal_transaksi,
                'keterangan' => $item->kategori,
                'jumlah' => $item->jumlah * $item->harga_per_satuan,
                'tipe' => 'pendapatan',
            ]);

        $pengeluaran = Pengeluaran::whereYear('tanggal_pembelian', $tahun)
            ->whereMonth('tanggal_pembelian', $bulan)
            ->orderBy('tanggal_pembelian', 'asc')
            ->get()
            ->map(fn($item) => [
                'tanggal' => $item->tanggal_pembelian,
                'keterangan' => $item->category . ' - ' . $item->description,
                'jumlah' => $item->jumlah * $item->harga_per_satuan,
                'tipe' => 'pengeluaran',
            ]);

        $transaksi = collect($pendapatan)->merge($pengeluaran)->sortBy('tanggal');
        $totalPendapatan = $pendapatan->sum('jumlah');
        $totalPengeluaran = $pengeluaran->sum('jumlah');
        $totalSaldo = $totalPendapatan - $totalPengeluaran;

        // Buat PDF
        $pdf = Pdf::loadView('cetak.laporan-keuangan', compact(
            'transaksi', 'totalPendapatan', 'totalPengeluaran', 'totalSaldo', 'namaBulan', 'tahun'
        ));

        return $pdf->download("Laporan-Keuangan-{$namaBulan}-{$tahun}.pdf");
    }
}