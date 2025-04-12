<?php

namespace App\Http\Controllers;

use App\Models\Pakan;
use App\Models\PenggunaanPakan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PenggunaanPakanController extends Controller
{
    public function storePenggunaanPakan(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama_pakan' => 'required|string|max:255',
                'tanggal_pakai' => 'required|date',
                'jumlah_pakai' => 'required|numeric|min:1',
            ], [
                'nama_pakan.required' => 'Nama pakan harus dipilih.',
                'nama_pakan.max' => 'Nama pakan maksimal 255 karakter.',
                'tanggal_pakai.required' => 'Tanggal pakai harus diisi.',
                'tanggal_pakai.date' => 'Format tanggal pakai tidak valid.',
                'jumlah_pakai.required' => 'Jumlah pakai harus diisi.',
                'jumlah_pakai.numeric' => 'Jumlah pakai harus berupa angka.',
                'jumlah_pakai.min' => 'Minimal jumlah pakai adalah 1 kg.'
            ]);

            $pakan = Pakan::where('nama_pakan', $request->nama_pakan)->first();

            if (!$pakan) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['nama_pakan' => 'Pakan tidak ditemukan.']);
            }

            if ($pakan->berat < $request->jumlah_pakai) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['jumlah_pakai' => "Stok pakan tidak mencukupi. Tersedia: {$pakan->berat} kg"]);
            }

            PenggunaanPakan::create([
                'pakan_id' => $pakan->id,
                'tanggal_pakai' => $request->tanggal_pakai,
                'jumlah_pakai' => $request->jumlah_pakai
            ]);

            $pakan->update([
                'berat' => $pakan->berat - $request->jumlah_pakai
            ]);

            return redirect()->route('food-management')->with([
                'status' => 'success',
                'message' => 'Penggunaan pakan berhasil disimpan.'
            ]);
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan penggunaan pakan: ' . $e->getMessage());
            return redirect()->route('food-management')->with([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan penggunaan pakan.'
            ]);
        }
    }
}
