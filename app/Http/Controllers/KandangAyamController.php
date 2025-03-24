<?php

namespace App\Http\Controllers;

use App\Models\KandangAyam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

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
            $kandang = KandangAyam::where('user_id', Auth::id())
             ->latest()
             ->paginate(4, ['*'], 'kandang_page', $kandangPage);

            return view('cage-management', compact('kandang'));
        } catch (\Exception $e) {
            Log::error('Gagal memuat data kandang: ' . $e->getMessage());

            return redirect()->route('cage-management')->with([
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
        // Validasi input
        $validated = $request->validate([
            'nama_kandang'    => 'required|string|max:255|unique:kandang_ayam',
            'kapasitas'       => 'required|integer|min:1', // Validasi kapasitas minimal 1
            'status_kandang'  => 'required|in:Aktif,Tidak Aktif',
        ], [
            'kapasitas.min' => 'Kapasitas kandang minimal 1.',  // Custom message untuk kapasitas
        ]);

        // Menambahkan ID user yang sedang login
        $validated['user_id'] = Auth::id();

        // Menyimpan data ke tabel KandangAyam
        KandangAyam::create($validated);

        // Redirect kembali dengan pesan sukses
        return redirect()->route('cage-management')->with([
            'status' => 'success',
            'message' => 'Kandang berhasil ditambahkan.',
        ]);
    } catch (ValidationException $e) {
        // Ambil pesan error pertama
        $errors = $e->validator->errors()->all();
        return redirect()->back()->with('status', 'error')->with('message', $errors[0]);
    } catch (\Exception $e) {
        // Jika terjadi kesalahan lainnya
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
            ], [
                'kapasitas.min' => 'Kapasitas kandang minimal 1.',  // Custom message untuk kapasitas
            ]);

            $kandang = KandangAyam::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
            $kandang->update($validated);   

            return redirect()->route('cage-management')->with([
                'status' => 'success',
                'message' => 'Kandang berhasil diperbarui.',
            ]);
        } catch (ValidationException $e) {
            // Ambil pesan error pertama
            $errors = $e->validator->errors()->all();
            return redirect()->back()->with('status', 'error')->with('message', $errors[0]);
        } catch (\Exception $e) {
            // Jika terjadi kesalahan lainnya
            Log::error('Gagal menyimpan kandang: ' . $e->getMessage());
    
            return redirect()->route('cage-management')->with([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan data kandang.',
            ]);
        }
    }

    /**
     * Menghapus data kandang.
     */
    public function destroyKandang($id)
    {
        try {
            $kandang = KandangAyam::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
            $kandang->delete(); 

            return response()->json(['success' => true, 'message' => 'Kandang berhasil dihapus.']);
        } catch (\Exception $e) {
            Log::error('Gagal menghapus kandang: ' . $e->getMessage());

            return response()->json(['success' => false, 'message' => 'Gagal menghapus kandang.'], 500);
        }
    }
}
