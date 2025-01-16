<?php

namespace App\Http\Controllers;

use App\Models\PopulasiAyam;
use App\Models\HarianAyam;
use Illuminate\Http\Request;

class PopulasiHarianController extends Controller
{
    public function storePopulasi(Request $request)
    {
        $request->validate([
            'kode_batch' => 'required|unique:populasi_ayam',
            'nama_batch' => 'required',
            'tanggal_doc' => 'required|date',
            'jumlah_ayam_masuk' => 'required|integer',
            'status_ayam' => 'required|in:Proses,Siap Panen,Sudah Panen',
        ]);

        $populasi = PopulasiAyam::create($request->all());

        return response()->json([
            'message' => 'Data Populasi Ayam berhasil disimpan',
            'data' => $populasi
        ], 201);
    }

    public function storeHarian(Request $request)
    {
        $request->validate([
            'id_populasi' => 'required|exists:populasi_ayam,id',
            'tanggal_input' => 'required|date',
            'jumlah_ayam_mati' => 'required|integer',
            'jumlah_ayam_sakit' => 'required|integer',
        ]);

        $populasi = PopulasiAyam::find($request->id_populasi);

        $harian = HarianAyam::create([
            'id_populasi' => $request->id_populasi,
            'nama_batch' => $populasi->nama_batch,
            'tanggal_input' => $request->tanggal_input,
            'jumlah_ayam_mati' => $request->jumlah_ayam_mati,
            'jumlah_ayam_sakit' => $request->jumlah_ayam_sakit,
        ]);

        return response()->json([
            'message' => 'Data Harian Ayam berhasil disimpan',
            'data' => $harian
        ], 201);
    }


    public function indexPopulasi()
    {
        $data = PopulasiAyam::all();
        return response()->json($data);
    }

    public function showHarian($id)
    {
        $data = HarianAyam::where('id_populasi', $id)->get();
        return response()->json($data);
    }
}
