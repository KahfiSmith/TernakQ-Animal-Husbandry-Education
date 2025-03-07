<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengeluaran;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PengeluaranController extends Controller
{
    /**
     * Menampilkan daftar pengeluaran dengan pagination.
     */
    public function indexPengeluaran(Request $request)
    {
        try {
            // Tangkap parameter query string untuk paginasi
            $pengeluaranPage = $request->get('pengeluaran_page', 1);

            // Ambil data pengeluaran dengan pagination (10 item per halaman)
            $pengeluaran = Pengeluaran::where('user_id', Auth::id())
                ->orderBy('tanggal_pembelian', 'desc')
                ->paginate(10, ['*'], 'pengeluaran_page', $pengeluaranPage);

            return view('finance-management-outcome', compact('pengeluaran'));
        } catch (\Exception $e) {
            Log::error('Gagal memuat data pengeluaran: ' . $e->getMessage());

            return redirect()->route('finance-management-outcome')->with([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memuat data pengeluaran.',
            ]);
        }
    }

    /**
     * Menyimpan data pengeluaran baru.
     */
    public function storePengeluaran(Request $request)
    {
        try {
            $validated = $request->validate([
                'category' => 'required|in:Pembelian Ayam,Pakan Ayam,"Obat, Vitamin, Vaksin"',
                'description' => 'required|string|max:255',
                'jumlah' => 'required|numeric|min:1',
                'satuan' => 'nullable|string|max:50',
                'harga_per_satuan' => 'nullable|numeric|min:0',
                'tanggal_pembelian' => 'required|date',
                'supplier' => 'nullable|string|max:255',
            ]);

            $validated['user_id'] = Auth::id();

            Pengeluaran::create($validated);

            return redirect()->route('finance-management-outcome')->with([
                'status' => 'success',
                'message' => 'Pengeluaran berhasil ditambahkan.',
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan pengeluaran: ' . $e->getMessage());

            return redirect()->route('finance-management-outcome')->with([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan pengeluaran.',
            ]);
        }
    }

    /**
     * Memperbarui data pengeluaran.
     */
    public function updatePengeluaran(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'category' => 'required|in:Pembelian Ayam,Pakan Ayam,"Obat, Vitamin, Vaksin"',
                'description' => 'required|string|max:255',
                'jumlah' => 'nullable|numeric|min:0',
                'satuan' => 'nullable|string|max:50',
                'harga_per_satuan' => 'nullable|numeric|min:0',
                'tanggal_pembelian' => 'required|date',
                'supplier' => 'nullable|string|max:255',
            ]);

            $pengeluaran = Pengeluaran::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

            $pengeluaran->update($validated);

            return redirect()->route('finance-management-outcome')->with([
                'status' => 'success',
                'message' => 'Pengeluaran berhasil diperbarui.',
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui pengeluaran: ' . $e->getMessage());

            return redirect()->route('finance-management-outcome')->with([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui pengeluaran.',
            ]);
        }
    }

    /**
     * Menghapus data pengeluaran.
     */
    public function destroyPengeluaran(Request $request, $id)
    {
        try {
            $pengeluaran = Pengeluaran::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $pengeluaran->delete();

            return response()->json(['success' => true, 'message' => 'Pengeluaran berhasil dihapus.']);
        } catch (\Exception $e) {
            Log::error('Gagal menghapus pengeluaran: ' . $e->getMessage());

            return response()->json(['success' => false, 'message' => 'Gagal menghapus pengeluaran.'], 500);
        }
    }
}
