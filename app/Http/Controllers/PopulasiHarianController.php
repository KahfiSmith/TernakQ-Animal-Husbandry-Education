<?php

namespace App\Http\Controllers;

use App\Models\PopulasiAyam;
use App\Models\HarianAyam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PopulasiHarianController extends Controller
{      
    public function indexChickenManagement(Request $request)
    {
        try {
            // Tangkap parameter query string untuk paginasi
            $populasiPage = $request->get('populasi_page', 1);
            $harianPage = $request->get('harian_page', 1);

            // Paginasi dengan appends
            $populasi = PopulasiAyam::latest()->paginate(5, ['*'], 'populasi_page', $populasiPage);
            $harian = HarianAyam::latest()->paginate(5, ['*'], 'harian_page', $harianPage);

            // Data untuk dropdown
            $batches = PopulasiAyam::all();

            // Return view dengan data
            return view('chicken-management', compact('populasi', 'harian', 'batches'));
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
                'batchCode' => 'required|unique:populasi_ayam,kode_batch',
                'batchName' => 'required|string|max:255',
                'docDate' => 'required|date',
                'chickenQuantity' => 'required|integer|min:1',
            ]);

            PopulasiAyam::create([
                'kode_batch' => $request->batchCode,
                'nama_batch' => $request->batchName,
                'tanggal_doc' => $request->docDate,
                'jumlah_ayam_masuk' => $request->chickenQuantity,
                'status_ayam' => 'Proses',
            ]);

            return redirect()->route('chicken-management')->with([
                'status' => 'success',
                'message' => 'Data Populasi Ayam berhasil disimpan.',
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan data populasi ayam: ' . $e->getMessage());

            return redirect()->route('chicken-management')->with([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan data populasi ayam.',
            ]);
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

            HarianAyam::create([
                'id_populasi' => $request->dailyBatchName,
                'nama_batch' => PopulasiAyam::find($request->dailyBatchName)->nama_batch,
                'tanggal_input' => $request->dailyDate,
                'jumlah_ayam_sakit' => $request->sickChicken,
                'jumlah_ayam_mati' => $request->deadChicken,
            ]);

            return redirect()->route('chicken-management')->with([
                'status' => 'success',
                'message' => 'Data Harian Ayam berhasil disimpan.',
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan data harian ayam: ' . $e->getMessage());

            return redirect()->route('chicken-management')->with([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan data harian ayam.',
            ]);
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
        // Validasi input, termasuk batchCodeSuffix
        $validated = $request->validate([
            'batchCodeSuffix' => 'required|digits:3', // Validasi untuk suffix
            'nama_batch' => 'required|string|max:255',
            'tanggal_doc' => 'required|date',
            'jumlah_ayam_masuk' => 'required|integer|min:0',
        ]);

        // Cari Populasi Ayam berdasarkan ID
        $populasi = PopulasiAyam::findOrFail($id);

        // Menggabungkan prefix dan suffix untuk batchCode
        $batchCode = 'BATCH-' . $validated['batchCodeSuffix'];

        // Update data
        $populasi->update([
            'kode_batch' => $batchCode, // Menggunakan batchCode yang telah digabungkan
            'nama_batch' => $validated['nama_batch'],
            'tanggal_doc' => $validated['tanggal_doc'],
            'jumlah_ayam_masuk' => $validated['jumlah_ayam_masuk'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data Populasi Ayam berhasil diperbarui.'
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        // Tangani kesalahan validasi
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal.',
            'errors' => $e->errors(),
        ], 422);
    } catch (\Exception $e) {
        // Log error
        Log::error('Gagal memperbarui Populasi Ayam: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan saat memperbarui data.'
        ], 500);
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

            // Cari Harian Ayam berdasarkan ID
            $harian = HarianAyam::findOrFail($id);

            // Dapatkan nama_batch berdasarkan id_populasi
            $namaBatch = PopulasiAyam::find($validated['dailyBatchName'])->nama_batch;

            // Update data
            $harian->update([
                'id_populasi' => $validated['dailyBatchName'],
                'nama_batch' => $namaBatch,
                'tanggal_input' => $validated['tanggal_input'],
                'jumlah_ayam_sakit' => $validated['jumlah_ayam_sakit'],
                'jumlah_ayam_mati' => $validated['jumlah_ayam_mati'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data Harian Ayam berhasil diperbarui.'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Tangani kesalahan validasi
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Log error
            Log::error('Gagal memperbarui Harian Ayam: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data.'
            ], 500);
        }
    }

}
