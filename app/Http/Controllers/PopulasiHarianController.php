<?php

namespace App\Http\Controllers;

use App\Models\PopulasiAyam;
use App\Models\HarianAyam;
use App\Models\KandangAyam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class PopulasiHarianController extends Controller
{      
    public function indexChickenManagement(Request $request)
    {
        try {
            $populasiPage = $request->get('populasi_page', 1);
            $harianPage = $request->get('harian_page', 1);
    
            $populasi = PopulasiAyam::with('kandang')  
                ->latest()
                ->paginate(5, ['*'], 'populasi_page', $populasiPage);
    
            $harian = HarianAyam::latest()->paginate(5, ['*'], 'harian_page', $harianPage);
    
            $batches = PopulasiAyam::all();
            $kandang = KandangAyam::where('status_kandang', 'Aktif')->get(); 
    
            return view('chicken-management', compact('populasi', 'harian', 'batches', 'kandang'));
        } catch (\Exception $e) {
            Log::error('Gagal memuat data: ' . $e->getMessage());
            return redirect()->back()->with([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memuat data.',
            ]);
        }
    }

    public function storePopulasi(Request $request)
    {
        try {
            $request->validate([
                // Ubah validasi menjadi alpha_num dan size:3 agar dapat menerima huruf dan angka, dengan panjang tepat 3 karakter
                'batchCodeSuffix' => 'required|alpha_num|size:3|unique:populasi_ayam,kode_batch',
                'batchName' => 'required|string|max:255',
                'docDate' => 'required|date',
                'chickenQuantity' => 'required|integer|min:1',
                'kandang_id' => 'required|exists:kandang_ayam,id'
            ]);

            $kandang = KandangAyam::findOrFail($request->kandang_id);

            // Hitung jumlah ayam yang sudah ada di kandang
            $totalAyamDiKandang = PopulasiAyam::where('kandang_id', $kandang->id)->sum('jumlah_ayam_masuk');

            // Validasi agar jumlah ayam yang akan ditambahkan tidak melebihi kapasitas kandang
            if ($totalAyamDiKandang + $request->chickenQuantity > $kandang->kapasitas) {
                return redirect()->back()->with(
                    'error',
                    'Jumlah ayam yang ingin ditambahkan melebihi kapasitas kandang.'
                );
            }

            // Gabungkan prefix "BATCH-" dengan suffix (ubah ke uppercase agar konsisten)
            $batchCode = 'BATCH-' . strtoupper($request->batchCodeSuffix);

            PopulasiAyam::create([
                'kode_batch' => $batchCode,
                'nama_batch' => $request->batchName,
                'tanggal_doc' => $request->docDate,
                'jumlah_ayam_masuk' => $request->chickenQuantity,
                'status_ayam' => 'Proses',
                'kandang_id' => $kandang->id, 
            ]);

            return redirect()->back()->with('success', 'Data Populasi Ayam berhasil disimpan.');
    } catch (\Exception $e) {
        Log::error('Gagal menyimpan data populasi ayam: ' . $e->getMessage());

        return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data.');
    }
    }

    public function storeHarian(Request $request)
    {
        try {
            $request->validate([
                'dailyBatchName' => 'required|exists:populasi_ayam,id',
                'dailyDate' => 'required|date',
                'sickChicken' => 'required|integer|min:0',
                'deadChicken' => 'required|integer|min:0',
            ]);

            $populasi = PopulasiAyam::findOrFail($request->dailyBatchName);

                // Hitung total ayam sakit & mati yang sudah tercatat di batch ini
            $totalMatiSebelumnya = HarianAyam::where('id_populasi', $populasi->id)->sum('jumlah_ayam_mati');
            $totalSakitSebelumnya = HarianAyam::where('id_populasi', $populasi->id)->sum('jumlah_ayam_sakit');

            // Hitung total ayam mati & sakit setelah input baru
            $totalMatiBaru = $totalMatiSebelumnya + $request->deadChicken;
            $totalSakitBaru = $totalSakitSebelumnya + $request->sickChicken;
            $totalKeseluruhan = $totalMatiBaru + $totalSakitBaru;

            // **Validasi jumlah ayam sakit + mati tidak boleh melebihi jumlah ayam dalam batch**
            if ($totalKeseluruhan > $populasi->jumlah_ayam_masuk) {
                return redirect()->back()->with(
                    'error',
                    'Jumlah ayam sakit dan mati melebihi jumlah ayam dalam batch ini.',
                );
            }

            // Simpan data harian ayam
            HarianAyam::create([
                'id_populasi' => $populasi->id,
                'nama_batch' => $populasi->nama_batch,
                'tanggal_input' => $request->dailyDate,
                'jumlah_ayam_sakit' => $request->sickChicken,
                'jumlah_ayam_mati' => $request->deadChicken,
            ]);

            // Jika semua ayam mati, ubah status batch ke `Sudah Panen`
            if ($totalMatiBaru == $populasi->jumlah_ayam_masuk) {
                $populasi->update(['status_ayam' => 'Sudah Panen']);
            }

            return redirect()->back()->with('success', 'Data Harian Ayam berhasil disimpan.');

        

    } catch (\Exception $e) {
        Log::error('Gagal menyimpan data harian ayam: ' . $e->getMessage());

        return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data.');
    }
        
    }

    public function destroyPopulasi($id)
    {
        try {
            $populasi = PopulasiAyam::findOrFail($id);
            $populasi->delete();

            return response()->json(['success' => true, 'message' => 'Data berhasil dihapus.']);
        } catch (\Exception $e) {
            Log::error('Gagal menghapus data populasi: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menghapus data.'], 500);
        }
    }

    public function destroyHarian($id)
    {
        try {
            $harian = HarianAyam::findOrFail($id);
            $harian->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'Data Harian Ayam berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal menghapus data harian ayam: ' . $e->getMessage());
    
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data harian ayam.'
            ], 500);
        }
    }

    public function updatePopulasi(Request $request, $id)
{
    try {
        // Validasi input
        $validated = $request->validate([
            'batchCodeSuffix' => 'required|alpha_num|size:3',
            'nama_batch' => 'required|string|max:255',
            'tanggal_doc' => 'required|date',
            'jumlah_ayam_masuk' => 'required|integer|min:0',
            'status_ayam' => 'required|in:Proses,Siap Panen,Sudah Panen',
            'kandang_id' => 'required|exists:kandang_ayam,id',
        ]);

        // Cari Populasi Ayam berdasarkan ID
        $populasi = PopulasiAyam::findOrFail($id);

        // Menggabungkan kode batch
        $batchCode = 'BATCH-' . strtoupper($validated['batchCodeSuffix']);

        // Validasi kapasitas kandang
        $kandang = KandangAyam::findOrFail($validated['kandang_id']);
        $totalAyamDiKandang = PopulasiAyam::where('kandang_id', $kandang->id)->sum('jumlah_ayam_masuk');

        if ($totalAyamDiKandang + $validated['jumlah_ayam_masuk'] > $kandang->kapasitas) {
            return redirect()->back()->with('error', 'Jumlah ayam melebihi kapasitas kandang.');
        }

        // Update data
        $populasi->update([
            'kode_batch' => $batchCode,
            'nama_batch' => $validated['nama_batch'],
            'tanggal_doc' => $validated['tanggal_doc'],
            'jumlah_ayam_masuk' => $validated['jumlah_ayam_masuk'],
            'status_ayam' => $validated['status_ayam'],
            'kandang_id' => $validated['kandang_id'],
        ]);

        return redirect()->back()->with('success', 'Data Populasi Ayam berhasil diperbarui.');

    } catch (\Exception $e) {
        Log::error('Gagal memperbarui Populasi Ayam: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data.');
    }
}

    
    public function updateHarian(Request $request, $id)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'dailyBatchName' => 'required|exists:populasi_ayam,id',
                'tanggal_input' => 'required|date',
                'jumlah_ayam_sakit' => 'required|integer|min:0',
                'jumlah_ayam_mati' => 'required|integer|min:0',
            ]);

            // Cari data Harian Ayam berdasarkan ID
            $harian = HarianAyam::findOrFail($id);

            // Cari Populasi Ayam berdasarkan batch yang dipilih
            $populasi = PopulasiAyam::findOrFail($validated['dailyBatchName']);

            // Validasi jumlah total ayam sakit + mati tidak boleh melebihi jumlah ayam masuk
            $totalAyamDipantau = HarianAyam::where('id_populasi', $populasi->id)->sum('jumlah_ayam_mati')
                                + HarianAyam::where('id_populasi', $populasi->id)->sum('jumlah_ayam_sakit')
                                - $harian->jumlah_ayam_sakit - $harian->jumlah_ayam_mati
                                + $validated['jumlah_ayam_sakit'] + $validated['jumlah_ayam_mati'];

            if ($totalAyamDipantau > $populasi->jumlah_ayam_masuk) {
                return redirect()->back()->with('error', 'Jumlah ayam sakit + mati melebihi jumlah ayam dalam batch ini.');
            }

            // Update data Harian Ayam
            $harian->update([
                'id_populasi' => $validated['dailyBatchName'],
                'nama_batch' => $populasi->nama_batch,
                'tanggal_input' => $validated['tanggal_input'],
                'jumlah_ayam_sakit' => $validated['jumlah_ayam_sakit'],
                'jumlah_ayam_mati' => $validated['jumlah_ayam_mati'],
            ]);

            return redirect()->back()->with('success', 'Data Harian Ayam berhasil diperbarui.');

        } catch (\Exception $e) {
            Log::error('Gagal memperbarui Harian Ayam: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }

    public function cetak($id)
    {
        try {
            // Ambil data populasi ayam dan data harian ayam terkait
            $populasi = PopulasiAyam::with('harianAyam')->findOrFail($id);

            // Load tampilan untuk PDF
            $pdf = Pdf::loadView('cetak.laporan-populasi', compact('populasi'));

            // Unduh PDF dengan nama file yang sesuai
            return $pdf->download("Laporan_Manajemen_Ayam_{$populasi->kode_batch}.pdf");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mencetak laporan.');
        }
    }

}   
