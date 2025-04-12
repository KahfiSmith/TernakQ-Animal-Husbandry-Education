<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengeluaran;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PengeluaranController extends Controller
{
    public function indexPengeluaran(Request $request)
    {
        try {
            $pengeluaranPage = $request->get('pengeluaran_page', 1);
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

    public function storePengeluaran(Request $request)
    {
        try {
            $validated = $request->validate([
                'category' => 'required|in:Pembelian Ayam,Pakan Ayam,Obat, Vitamin, Vaksin',
                'description' => 'required|string|max:255',
                'jumlah' => 'required|numeric|min:1',
                'satuan' => 'required|in:ekor,kg,karung,botol,unit,paket',
                'harga_per_satuan' => 'required|numeric|min:1',
                'tanggal_pembelian' => 'required|date',
                'supplier' => 'nullable|string|max:255',
            ], [
                'category.required' => 'Kategori harus dipilih.',
                'category.in' => 'Kategori tidak valid.',
                'description.required' => 'Deskripsi pengeluaran harus diisi.',
                'description.max' => 'Deskripsi pengeluaran maksimal 255 karakter.',
                'jumlah.required' => 'Jumlah harus diisi.',
                'jumlah.numeric' => 'Jumlah harus berupa angka.',
                'jumlah.min' => 'Jumlah minimal 1.',
                'satuan.required' => 'Satuan harus dipilih.',
                'satuan.in' => 'Satuan tidak valid.',
                'harga_per_satuan.required' => 'Harga per satuan harus diisi.',
                'harga_per_satuan.numeric' => 'Harga per satuan harus berupa angka.',
                'harga_per_satuan.min' => 'Harga per satuan minimal Rp 1.',
                'tanggal_pembelian.required' => 'Tanggal pembelian harus diisi.',
                'tanggal_pembelian.date' => 'Format tanggal pembelian tidak valid.',
                'supplier.max' => 'Nama supplier maksimal 255 karakter.',
            ]);

            $validated['user_id'] = Auth::id();
            $validated['total_biaya'] = $request->jumlah * $request->harga_per_satuan;

            Pengeluaran::create($validated);

            return redirect()->route('finance-management-outcome')->with([
                'status' => 'success',
                'message' => 'Pengeluaran berhasil ditambahkan.',
            ]);

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan pengeluaran: ' . $e->getMessage());

            return redirect()->route('finance-management-outcome')->with([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan pengeluaran.',
            ]);
        }
    }

    public function updatePengeluaran(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'category' => 'required|in:Pembelian Ayam,Pakan Ayam,Obat, Vitamin, Vaksin',
                'description' => 'required|string|max:255',
                'jumlah' => 'required|numeric|min:1',
                'satuan' => 'required|in:ekor,kg,karung,botol,unit,paket',
                'harga_per_satuan' => 'required|numeric|min:1',
                'tanggal_pembelian' => 'required|date',
                'supplier' => 'nullable|string|max:255',
            ], [
                'category.required' => 'Kategori harus dipilih.',
                'category.in' => 'Kategori tidak valid.',
                'description.required' => 'Deskripsi pengeluaran harus diisi.',
                'description.max' => 'Deskripsi pengeluaran maksimal 255 karakter.',
                'jumlah.required' => 'Jumlah harus diisi.',
                'jumlah.numeric' => 'Jumlah harus berupa angka.',
                'jumlah.min' => 'Jumlah minimal 1.',
                'satuan.required' => 'Satuan harus dipilih.',
                'satuan.in' => 'Satuan tidak valid.',
                'harga_per_satuan.required' => 'Harga per satuan harus diisi.',
                'harga_per_satuan.numeric' => 'Harga per satuan harus berupa angka.',
                'harga_per_satuan.min' => 'Harga per satuan minimal Rp 1.',
                'tanggal_pembelian.required' => 'Tanggal pembelian harus diisi.',
                'tanggal_pembelian.date' => 'Format tanggal pembelian tidak valid.',
                'supplier.max' => 'Nama supplier maksimal 255 karakter.',
            ]);
            
            $pengeluaran = Pengeluaran::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();
                
            $validated['total_biaya'] = $request->jumlah * $request->harga_per_satuan;
            $pengeluaran->update($validated);

            return redirect()->route('finance-management-outcome')->with([
                'status' => 'success',
                'message' => 'Pengeluaran berhasil diperbarui.',
            ]);

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (ModelNotFoundException $e) {
            return redirect()->route('finance-management-outcome')->with([
                'status' => 'error',
                'message' => 'Data pengeluaran tidak ditemukan.',
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui pengeluaran: ' . $e->getMessage());

            return redirect()->route('finance-management-outcome')->with([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui pengeluaran.',
            ]);
        }
    }

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
