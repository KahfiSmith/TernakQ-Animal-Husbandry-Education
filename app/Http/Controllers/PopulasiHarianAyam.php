<?php

namespace App\Http\Controllers;

use App\Models\PopulasiAyam;
use App\Models\HarianAyam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PopulasiHarianController extends Controller
{
    /**
     * Simpan data populasi ayam.
     */
    public function storePopulasi(Request $request)
    {
        try {
            $request->validate([
                'kode_batch' => 'required|unique:populasi_ayam,kode_batch',
                'nama_batch' => 'required|string|max:255',
                'tanggal_doc' => 'required|date',
                'jumlah_ayam_masuk' => 'required|integer|min:1',
                'status_ayam' => 'required|in:Proses,Siap Panen,Sudah Panen',
            ]);

            $populasi = PopulasiAyam::create([
                'kode_batch' => $request->kode_batch,
                'nama_batch' => $request->nama_batch,
                'tanggal_doc' => $request->tanggal_doc,
                'jumlah_ayam_masuk' => $request->jumlah_ayam_masuk,
                'status_ayam' => $request->status_ayam,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data Populasi Ayam berhasil disimpan.',
                'data' => $populasi
            ], 201);

        } catch (\Exception $e) {
            Log::error('Gagal menyimpan data populasi ayam: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data.',
            ], 500);
        }
    }

    /**
     * Simpan data harian ayam.
     */
    public function storeHarian(Request $request)
    {
        try {
            $request->validate([
                'id_populasi' => 'required|exists:populasi_ayam,id',
                'tanggal_input' => 'required|date',
                'jumlah_ayam_mati' => 'required|integer|min:0',
                'jumlah_ayam_sakit' => 'required|integer|min:0',
            ]);

            $populasi = PopulasiAyam::findOrFail($request->id_populasi);

            $harian = HarianAyam::create([
                'id_populasi' => $request->id_populasi,
                'nama_batch' => $populasi->nama_batch,
                'tanggal_input' => $request->tanggal_input,
                'jumlah_ayam_mati' => $request->jumlah_ayam_mati,
                'jumlah_ayam_sakit' => $request->jumlah_ayam_sakit,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data Harian Ayam berhasil disimpan.',
                'data' => $harian
            ], 201);

        } catch (\Exception $e) {
            Log::error('Gagal menyimpan data harian ayam: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data harian.',
            ], 500);
        }
    }

    /**
     * Tampilkan semua data populasi ayam.
     */
    public function indexPopulasi()
    {
        $data = PopulasiAyam::all();

        return response()->json([
            'success' => true,
            'message' => 'Data Populasi Ayam berhasil diambil.',
            'data' => $data
        ]);
    }

    /**
     * Tampilkan data harian ayam berdasarkan ID populasi.
     */
    public function showHarian($id)
    {
        $data = HarianAyam::where('id_populasi', $id)->get();

        if ($data->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data Harian Ayam berhasil diambil.',
            'data' => $data
        ]);
    }
}
