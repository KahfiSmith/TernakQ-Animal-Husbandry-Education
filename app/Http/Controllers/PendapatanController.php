<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendapatan;
use Illuminate\Support\Facades\Log;

class PendapatanController extends Controller
{
    /**
     * Menampilkan daftar pendapatan dengan pagination.
     */
    public function indexPendapatan(Request $request)
    {
        try {
            // Tangkap parameter query string untuk paginasi
            $pendapatanPage = $request->get('pendapatan_page', 1);

            // Ambil data pendapatan dengan pagination (10 item per halaman)
            $pendapatan = Pendapatan::orderBy('tanggal_transaksi', 'desc')
                ->paginate(10, ['*'], 'pendapatan_page', $pendapatanPage);

            return view('finance-management-income', compact('pendapatan'));
        } catch (\Exception $e) {
            Log::error('Gagal memuat data pendapatan: ' . $e->getMessage());

            return redirect()->route('finance-management-income')->with([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memuat data pendapatan.',
            ]);
        }
    }

    /**
     * Menyimpan data pendapatan baru.
     */
    public function storePendapatan(Request $request)
    {
        try {
            $request->validate([
                'kategori' => 'required|in:Penjualan Ayam,Penjualan Kotoran,Pendapatan Kemitraan',
                'jumlah' => 'required|numeric|min:1',
                'satuan' => 'required|in:ekor,kg,karung',
                'harga_per_satuan' => 'required|numeric|min:0',
                'tanggal_transaksi' => 'required|date',
                'nama_pembeli' => 'nullable|string|max:255',
                'nama_perusahaan' => 'nullable|string|max:255',
            ]);

            Pendapatan::create($request->all());

            return redirect()->route('finance-management-income')->with([
                'status' => 'success',
                'message' => 'Pendapatan berhasil ditambahkan.',
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan pendapatan: ' . $e->getMessage());

            return redirect()->route('finance-management-income')->with([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan pendapatan.',
            ]);
        }
    }

    /**
     * Memperbarui data pendapatan.
     */
    public function updatePendapatan(Request $request, $id)
    {
        try {
            $pendapatan = Pendapatan::findOrFail($id);

            $request->validate([
                'kategori' => 'required|in:penjualan_ayam,penjualan_kotoran,kemitraan',
                'jumlah' => 'required|numeric|min:1',
                'satuan' => 'required|in:ekor,kg,karung',
                'harga_per_satuan' => 'required|numeric|min:0',
                'tanggal_transaksi' => 'required|date',
                'nama_pembeli' => 'nullable|string|max:255',
                'nama_perusahaan' => 'nullable|string|max:255',
            ]);

            $pendapatan->update($request->all());

            return redirect()->route('finance-management-income')->with([
                'status' => 'success',
                'message' => 'Pendapatan berhasil diperbarui.',
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui pendapatan: ' . $e->getMessage());

            return redirect()->route('finance-management-income')->with([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui pendapatan.',
            ]);
        }
    }

    /**
     * Menghapus data pendapatan.
     */
    public function destroyPendapatan(Request $request, $id)
    {
        try {
            $pendapatan = Pendapatan::findOrFail($id);
            $pendapatan->delete();

            return response()->json(['success' => true, 'message' => 'Pendapatan berhasil dihapus.']);
        } catch (\Exception $e) {
            Log::error('Gagal menghapus pendapatan: ' . $e->getMessage());

            return response()->json(['success' => false, 'message' => 'Gagal menghapus pendapatan.'], 500);
        }
    }
}
