<?php

namespace App\Http\Controllers;

use App\Models\Pakan;
use App\Models\PenggunaanPakan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PenggunaanPakanController extends Controller
{
    public function storePenggunaanPakan(Request $request)
{
    try {
        $request->validate([
            'nama_pakan' => 'required|string|max:255',
            'tanggal_pakai' => 'required|date',
            'jumlah_pakai' => 'required|numeric|min:0',
        ]);

        // Cari pakan berdasarkan nama
        $pakan = Pakan::where('nama_pakan', $request->nama_pakan)->first();

        if (!$pakan) {
            return redirect()->route('food-management')->with([
                'status' => 'errir',
                'message' => 'Pakan tidak ditambahkan.'
            ]);
        }

        // Cek apakah stok mencukupi
        if ($pakan->berat < $request->jumlah_pakai) {
            return redirect()->route('food-management')->with([
                'status' => 'error',
                'message' => 'Stok pakan tidak mencukupi.'
            ]);
        }

        // Simpan penggunaan pakan
        PenggunaanPakan::create([
            'pakan_id' => $pakan->id,
            'tanggal_pakai' => $request->tanggal_pakai,
            'jumlah_pakai' => $request->jumlah_pakai
        ]);

        // Kurangi stok pakan
        $pakan->update([
            'berat' => $pakan->berat - $request->jumlah_pakai
        ]);

        return redirect()->route('food-management')->with([
            'status' => 'success',
            'message' => 'Penggunaan pakan berhasil disimpan.'
        ]);
    } catch (\Exception $e) {
        Log::error('Gagal menyimpan penggunaan pakan: ' . $e->getMessage());
        return redirect()->route('food-management')->with
        ([
            'status' => 'error',
            'message' => 'Terjadi kesalahan saat menyimpan penggunaan pakan.'
        ]);
    }
}

public function destroyPenggunaanPakan($id)
{
    try {
        $penggunaan = PenggunaanPakan::findOrFail($id);
        
        // Tambahkan kembali stok pakan yang digunakan
        $pakan = Pakan::find($penggunaan->pakan_id);
        if ($pakan) {
            $pakan->update(['berat' => $pakan->berat + $penggunaan->jumlah_pakai]);
        }

        $penggunaan->delete();

        return redirect()->back()->with('success', 'Penggunaan pakan berhasil dihapus.');
    } catch (\Exception $e) {
        Log::error('Gagal menghapus penggunaan pakan: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus penggunaan pakan.');
    }
}



}
