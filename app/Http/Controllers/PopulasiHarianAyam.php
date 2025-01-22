<?php

namespace App\Http\Controllers;

use App\Models\PopulasiAyam;
use App\Models\HarianAyam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PopulasiHarianController extends Controller
{
    public function storePopulasi(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'batchCode' => 'required|unique:populasi_ayam,kode_batch',
                'batchName' => 'required|string|max:255',
                'docDate' => 'required|date',
                'chickenQuantity' => 'required|integer|min:1',
            ]);

            // Simpan data ke database
            $populasi = PopulasiAyam::create([
                'kode_batch' => $request->batchCode,
                'nama_batch' => $request->batchName,
                'tanggal_doc' => $request->docDate,
                'jumlah_ayam_masuk' => $request->chickenQuantity,
                'status_ayam' => 'Proses', // Status default untuk populasi baru
            ]);

            // Redirect kembali ke halaman sebelumnya dengan pesan sukses
            return redirect()
                ->back()
                ->with('success', 'Data Populasi Ayam berhasil disimpan.');

        } catch (\Exception $e) {
            // Log error jika terjadi masalah
            Log::error('Gagal menyimpan data populasi ayam: ' . $e->getMessage());

            // Redirect kembali dengan pesan error
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }
    }

    public function storeHarian(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'dailyBatchName' => 'required|string|max:255',
                'dailyDate' => 'required|date',
                'sickChicken' => 'required|integer|min:0',
                'deadChicken' => 'required|integer|min:0',
            ]);

            // Cari batch berdasarkan nama (opsional, tergantung logika database)
            $batch = PopulasiAyam::where('nama_batch', $request->dailyBatchName)->first();

            if (!$batch) {
                return redirect()->back()->with('error', 'Batch tidak ditemukan.');
            }

            // Simpan data harian
            $harian = HarianAyam::create([
                'id_populasi' => $batch->id,
                'nama_batch' => $request->dailyBatchName,
                'tanggal_input' => $request->dailyDate,
                'jumlah_ayam_sakit' => $request->sickChicken,
                'jumlah_ayam_mati' => $request->deadChicken,
            ]);

            // Redirect kembali dengan pesan sukses
            return redirect()
                ->back()
                ->with('success', 'Data Harian Ayam berhasil disimpan.');

        } catch (\Exception $e) {
            // Log error jika terjadi masalah
            Log::error('Gagal menyimpan data harian ayam: ' . $e->getMessage());

            // Redirect kembali dengan pesan error
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan data harian. Silakan coba lagi.');
        }
    }

    public function indexPopulasi()
    {
        try {
            $data = PopulasiAyam::paginate(10); // Pagination

            return response()->json([
                'success' => true,
                'message' => 'Data Populasi Ayam berhasil diambil.',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal mengambil data populasi ayam: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data populasi ayam.',
            ], 500);
        }
    }

    public function showHarian($id)
    {
        try {
            $data = HarianAyam::where('id_populasi', $id)->get();

            if ($data->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data Harian Ayam berhasil diambil.',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal mengambil data harian ayam: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data harian ayam.',
            ], 500);
        }
    }
}
