<?php

namespace App\Http\Controllers;

use App\Models\Pakan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PakanController extends Controller
{
    public function indexPakan(Request $request)
{
    try {
        // Paginasi untuk stok pakan
        $pakanPage = $request->get('pakan_page', 1);
        $pakan = Pakan::latest()->paginate(5, ['*'], 'pakan_page', $pakanPage);

        return view('food-management', compact('pakan'));
    } catch (\Exception $e) {
        Log::error('Gagal memuat data pakan: ' . $e->getMessage());

        return redirect()->route('food-management')->with([
            'status' => 'error',
            'message' => 'Terjadi kesalahan saat memuat data pakan.',
        ]);
    }
}

public function storePakan(Request $request)
{
    try {
        $request->validate([
            'nama_pakan' => 'required|string|max:255',
            'jenis_pakan' => 'required|string|max:255',
            'berat' => 'required|numeric|min:0',
            'tanggal_masuk' => 'required|date',
            'harga_per_kg' => 'required|numeric|min:0'
        ]);

        Pakan::create($request->all());

        return redirect()->route('food-management')->with([
            'status' => 'success',
            'message' => 'Pakan berhasil ditambahkan.'
        ]);
    } catch (\Exception $e) {
        Log::error('Gagal menambah pakan: ' . $e->getMessage());
        return redirect()->route('food-management')->with([
            'status' => 'error',
            'message' => 'Terjadi kesalahan saat menambahkan pakan.'
        ]);
    }
}

public function updatePakan(Request $request, $id)
{
    try {
        $request->validate([
            'nama_pakan' => 'required|string|max:255',
            'jenis_pakan' => 'required|string|max:255',
            'berat' => 'required|numeric|min:0',
            'tanggal_masuk' => 'required|date',
            'harga_per_kg' => 'required|numeric|min:0'
        ]);

        $pakan = Pakan::findOrFail($id);
        $pakan->update($request->all());

        return redirect()->route('food-management')->with([
            'status' => 'success',
            'message' => 'Pakan berhasil diperbarui.'
        ]);
    } catch (\Exception $e) {
        Log::error('Gagal memperbarui pakan: ' . $e->getMessage());
        return redirect()->route('food-management')->with([
            'status' => 'error',
            'message' => 'Terjadi kesalahan saat memperbarui pakan.'
        ]);
    }
}

public function destroyPakan($id)
{
    try {
        $pakan = Pakan::findOrFail($id);
        $pakan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pakan berhasil dihapus.'
        ]);
    } catch (\Exception $e) {
        Log::error('Gagal menghapus pakan: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan saat menghapus pakan.'
        ], 500);
    }
}


}
