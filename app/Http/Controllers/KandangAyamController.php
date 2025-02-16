<?php

namespace App\Http\Controllers;

use App\Models\KandangAyam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KandangAyamController extends Controller
{
    /**
     * Menampilkan daftar kandang ayam dengan pagination.
     */
    public function indexKandangManagement(Request $request)
    {
        try {
            // Tangkap parameter query string untuk paginasi
            $kandangPage = $request->get('kandang_page', 1);

            // Paginasi dengan appends
            $kandang = KandangAyam::latest()->paginate(4, ['*'], 'kandang_page', $kandangPage);

            return view('cage-management', compact('kandang'));
        } catch (\Exception $e) {
            Log::error('Gagal memuat data kandang: ' . $e->getMessage());

            return redirect()->back()->with([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memuat data kandang.',
            ]);
        }
    }

    /**
     * Menyimpan data kandang ayam baru.
     */
    public function storeKandang(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama_kandang'    => 'required|string|max:255|unique:kandang_ayam',
                'kapasitas'       => 'required|integer|min:1',
                'status_kandang'  => 'required|in:Aktif,Tidak Aktif',
            ]);

            KandangAyam::create($validated);

            return redirect()->route('cage-management')->with([
                'status' => 'success',
                'message' => 'Kandang berhasil ditambahkan.',
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan kandang: ' . $e->getMessage());

            return redirect()->route('cage-management')->with([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan data kandang.',
            ]);
        }
    }

    /**
     * Memperbarui data kandang yang sudah ada.
     */
    public function updateKandang(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'nama_kandang'   => 'required|string|max:255|unique:kandang_ayam,nama_kandang,' . $id,
                'kapasitas'      => 'required|integer|min:1',
                'status_kandang' => 'required|in:Aktif,Tidak Aktif',
            ]);

            $kandang = KandangAyam::findOrFail($id);
            $kandang->update($validated);

            return redirect()->route('cage-management')->with([
                'status' => 'success',
                'message' => 'Kandang berhasil diperbarui.',
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui kandang: ' . $e->getMessage());

            return redirect()->route('cage-management')->with([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui kandang.',
            ]);
        }
    }

    /**
     * Menghapus data kandang.
     */
    public function destroyKandang($id)
    {
        try {
            $kandang = KandangAyam::findOrFail($id);
            $kandang->delete();

            return response()->json(['success' => true, 'message' => 'Kandang berhasil dihapus.']);
        } catch (\Exception $e) {
            Log::error('Gagal menghapus kandang: ' . $e->getMessage());

            return response()->json(['success' => false, 'message' => 'Gagal menghapus kandang.'], 500);
        }
    }
}
