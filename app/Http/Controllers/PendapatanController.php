<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendapatan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PendapatanController extends Controller
{

    public function indexPendapatan(Request $request)
    {
        try {
            $pendapatanPage = $request->get('pendapatan_page', 1);

            $pendapatan = Pendapatan::where('user_id', Auth::id())
                ->orderBy('tanggal_transaksi', 'desc')
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

    public function storePendapatan(Request $request)
    {
        try {
            $validated = $request->validate([
                'kategori' => 'required|in:Penjualan Ayam,Penjualan Kotoran,Pendapatan Kemitraan',
                'jumlah' => 'required|numeric|min:1',
                'satuan' => 'required|in:ekor,kg,karung',
                'harga_per_satuan' => 'required|numeric|min:1000',
                'tanggal_transaksi' => 'required|date',
                'nama_pembeli' => 'nullable|string|max:255',
                'nama_perusahaan' => 'nullable|string|max:255',
            ], [
                'kategori.required' => 'Kategori harus dipilih.',
                'kategori.in' => 'Kategori tidak valid.',
                'jumlah.required' => 'Jumlah harus diisi.',
                'jumlah.numeric' => 'Jumlah harus berupa angka.',
                'jumlah.min' => 'Jumlah minimal 1.',
                'satuan.required' => 'Satuan harus dipilih.',
                'satuan.in' => 'Satuan tidak valid.',
                'harga_per_satuan.required' => 'Harga per satuan harus diisi.',
                'harga_per_satuan.numeric' => 'Harga per satuan harus berupa angka.',
                'harga_per_satuan.min' => 'Harga per satuan minimal Rp 1.000.',
                'tanggal_transaksi.required' => 'Tanggal transaksi harus diisi.',
                'tanggal_transaksi.date' => 'Format tanggal transaksi tidak valid.',
                'nama_pembeli.max' => 'Nama pembeli maksimal 255 karakter.',
                'nama_perusahaan.max' => 'Nama perusahaan maksimal 255 karakter.'
            ]);

            $validated['user_id'] = Auth::id();

            Pendapatan::create($validated);

            return redirect()->route('finance-management-income')->with([
                'status' => 'success',
                'message' => 'Pendapatan berhasil ditambahkan.',
            ]);

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan pendapatan: ' . $e->getMessage());

            return redirect()->route('finance-management-income')->with([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan pendapatan.',
            ]);
        }
    }

    public function updatePendapatan(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'kategori' => 'required|in:Penjualan Ayam,Penjualan Kotoran,Pendapatan Kemitraan',
                'jumlah' => 'required|numeric|min:1',
                'satuan' => 'required|in:ekor,kg,karung',
                'harga_per_satuan' => 'required|numeric|min:1000',
                'tanggal_transaksi' => 'required|date',
                'nama_pembeli' => 'nullable|string|max:255',
                'nama_perusahaan' => 'nullable|string|max:255',
            ], [
                'kategori.required' => 'Kategori harus dipilih.',
                'kategori.in' => 'Kategori tidak valid.',
                'jumlah.required' => 'Jumlah harus diisi.',
                'jumlah.numeric' => 'Jumlah harus berupa angka.',
                'jumlah.min' => 'Jumlah minimal 1.',
                'satuan.required' => 'Satuan harus dipilih.',
                'satuan.in' => 'Satuan tidak valid.',
                'harga_per_satuan.required' => 'Harga per satuan harus diisi.',
                'harga_per_satuan.numeric' => 'Harga per satuan harus berupa angka.',
                'harga_per_satuan.min' => 'Harga per satuan minimal Rp 1.000.',
                'tanggal_transaksi.required' => 'Tanggal transaksi harus diisi.',
                'tanggal_transaksi.date' => 'Format tanggal transaksi tidak valid.',
                'nama_pembeli.max' => 'Nama pembeli maksimal 255 karakter.',
                'nama_perusahaan.max' => 'Nama perusahaan maksimal 255 karakter.'
            ]);
            
            $pendapatan = Pendapatan::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $pendapatan->update($validated);

            return redirect()->route('finance-management-income')->with([
                'status' => 'success',
                'message' => 'Pendapatan berhasil diperbarui.',
            ]);

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (ModelNotFoundException $e) {
            return redirect()->route('finance-management-income')->with([
                'status' => 'error',
                'message' => 'Data pendapatan tidak ditemukan.',
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui pendapatan: ' . $e->getMessage());

            return redirect()->route('finance-management-income')->with([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui pendapatan.',
            ]);
        }
    }

    public function destroyPendapatan(Request $request, $id)
    {
        try {
            $pendapatan = Pendapatan::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $pendapatan->delete();

            return response()->json(['success' => true, 'message' => 'Pendapatan berhasil dihapus.']);
        } catch (\Exception $e) {
            Log::error('Gagal menghapus pendapatan: ' . $e->getMessage());

            return response()->json(['success' => false, 'message' => 'Gagal menghapus pendapatan.'], 500);
        }
    }
}
