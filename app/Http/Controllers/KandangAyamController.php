<?php

namespace App\Http\Controllers;

use App\Models\KandangAyam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class KandangAyamController extends Controller
{
    public function indexKandangManagement(Request $request)
    {
        try {
            $kandangPage = $request->get('kandang_page', 1);
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

    public function storeKandang(Request $request)
    {
        $validated = $request->validate([
            'nama_kandang'    => 'required|string|max:255|unique:kandang_ayam,nama_kandang',
            'kapasitas'       => 'required|integer|min:1', 
            'status_kandang'  => 'required|in:Aktif,Tidak Aktif',
        ], [
            'nama_kandang.required' => 'Nama kandang harus diisi.',
            'nama_kandang.unique' => 'Nama kandang sudah ada, pilih nama lain.',
            'nama_kandang.max' => 'Nama kandang maksimal 255 karakter.',
            'kapasitas.required' => 'Kapasitas kandang harus diisi.',
            'kapasitas.integer' => 'Kapasitas harus berupa angka.',
            'kapasitas.min' => 'Kapasitas kandang minimal 1.',
            'status_kandang.required' => 'Status kandang harus dipilih.',
            'status_kandang.in' => 'Status kandang tidak valid.',
        ]);

        try {
            $validated['user_id'] = Auth::id();
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
    
    public function updateKandang(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_kandang'    => 'required|string|max:255|unique:kandang_ayam,nama_kandang,'.$id.',id',
            'kapasitas'       => 'required|integer|min:1', 
            'status_kandang'  => 'required|in:Aktif,Tidak Aktif',
        ], [
            'nama_kandang.required' => 'Nama kandang harus diisi.',
            'nama_kandang.unique' => 'Nama kandang sudah ada, pilih nama lain.',
            'nama_kandang.max' => 'Nama kandang maksimal 255 karakter.',
            'kapasitas.required' => 'Kapasitas kandang harus diisi.',
            'kapasitas.integer' => 'Kapasitas harus berupa angka.',
            'kapasitas.min' => 'Kapasitas kandang minimal 1.',
            'status_kandang.required' => 'Status kandang harus dipilih.',
            'status_kandang.in' => 'Status kandang tidak valid.',
        ]);

        try {
            $kandang = KandangAyam::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
            $kandang->update($validated);   
            return redirect()->route('cage-management')->with([
                'status' => 'success',
                'message' => 'Kandang berhasil diperbarui.',
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan kandang: ' . $e->getMessage());
            return redirect()->route('cage-management')->with([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan data kandang.',
            ]);
        }
    }

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
