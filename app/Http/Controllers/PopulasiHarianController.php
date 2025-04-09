<?php

namespace App\Http\Controllers;

use App\Models\PopulasiAyam;
use App\Models\HarianAyam;
use App\Models\KandangAyam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class PopulasiHarianController extends Controller
{      
    public function indexChickenManagement(Request $request)
    {
        try {
            $populasiPage = $request->get('populasi_page', 1);
            $harianPage = $request->get('harian_page', 1);
            $populasi = PopulasiAyam::with('kandang') 
                ->where('user_id', Auth::id()) 
                ->latest()
                ->paginate(5, ['*'], 'populasi_page', $populasiPage);
            $harian = HarianAyam::whereHas('populasiAyam', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->latest()
            ->paginate(5, ['*'], 'harian_page', $harianPage);
    
            $batches = PopulasiAyam::all();
            $kandang = KandangAyam::where('status_kandang', 'Aktif')->get(); 
            $userId = Auth::id();  
            $monthlyData = DB::table('harian_ayam')
                ->join('populasi_ayam', 'harian_ayam.id_populasi', '=', 'populasi_ayam.id')
                ->whereIn('populasi_ayam.status_ayam', ['Proses', 'Siap Panen'])
                ->where('populasi_ayam.user_id', $userId)  
                ->select(
                    DB::raw('MONTH(harian_ayam.tanggal_input) as month'),
                    DB::raw('SUM(harian_ayam.jumlah_ayam_sakit) as sick'),
                    DB::raw('SUM(harian_ayam.jumlah_ayam_mati) as dead')
                )
                ->whereYear('harian_ayam.tanggal_input', Carbon::now()->year)
                ->groupBy(DB::raw('MONTH(harian_ayam.tanggal_input)'))
                ->orderBy('month')
                ->get();

            $todayData = DB::table('harian_ayam')
                ->join('populasi_ayam', 'harian_ayam.id_populasi', '=', 'populasi_ayam.id')
                ->whereIn('populasi_ayam.status_ayam', ['Proses', 'Siap Panen'])
                ->where('populasi_ayam.user_id', $userId)  
                ->whereDate('harian_ayam.tanggal_input', Carbon::today())
                ->select(
                    DB::raw('SUM(harian_ayam.jumlah_ayam_sakit) as sick'),
                    DB::raw('SUM(harian_ayam.jumlah_ayam_mati) as dead')
                )
                ->first();
    
            return view('chicken-management', compact('populasi', 'harian', 'batches', 'kandang', 'monthlyData', 'todayData'));
        } catch (\Exception $e) {
            Log::error('Gagal memuat data: ' . $e->getMessage());
            return redirect()->back()->with([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memuat data.',
            ]);
        }
    }

    public function storePopulasi(Request $request)
    {
        try {
            $request->validate([
                'kandang_id' => 'required|exists:kandang_ayam,id',
                'batchCodeSuffix' => 'required|alpha_num|size:3|unique:populasi_ayam,kode_batch',
                'batchName' => 'required|string|max:255',
                'docDate' => 'required|date',
                'chickenQuantity' => 'required|integer|min:1',
            ], [
                'kandang_id.required' => 'Kandang harus dipilih.',
                'kandang_id.exists' => 'Kandang yang dipilih tidak valid.',
                'batchCodeSuffix.required' => 'Kode populasi harus diisi.',
                'batchCodeSuffix.alpha_num' => 'Kode populasi hanya boleh berisi huruf dan angka.',
                'batchCodeSuffix.size' => 'Kode populasi harus terdiri dari 3 karakter.',
                'batchCodeSuffix.unique' => 'Kode populasi ini sudah digunakan, silakan gunakan kode lain.',
                'batchName.required' => 'Nama populasi harus diisi.',
                'batchName.max' => 'Nama populasi maksimal 255 karakter.',
                'docDate.required' => 'Tanggal DOC harus diisi.',
                'docDate.date' => 'Format tanggal DOC tidak valid.',
                'docDate.before_or_equal' => 'Tanggal DOC tidak boleh lebih dari hari ini.',
                'chickenQuantity.required' => 'Jumlah ayam masuk harus diisi.',
                'chickenQuantity.integer' => 'Jumlah ayam masuk harus berupa angka.',
                'chickenQuantity.min' => 'Jumlah ayam masuk minimal 1 ekor.',
            ]);

            $kandang = KandangAyam::where('id', $request->kandang_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

            $totalAyamDiKandang = PopulasiAyam::where('kandang_id', $kandang->id)
                ->where('user_id', Auth::id())
                ->sum('jumlah_ayam_masuk');

            if ($totalAyamDiKandang + $request->chickenQuantity > $kandang->kapasitas) {
                return redirect()->back()->with(
                    'error',
                    'Jumlah ayam yang ingin ditambahkan melebihi kapasitas kandang.'
                );
            }
            $batchCode = 'POPULASI-' . strtoupper($request->batchCodeSuffix);

            PopulasiAyam::create([
                'kode_batch' => $batchCode,
                'nama_batch' => $request->batchName,
                'tanggal_doc' => $request->docDate,
                'jumlah_ayam_masuk' => $request->chickenQuantity,
                'status_ayam' => 'Proses',
                'kandang_id' => $kandang->id, 
                'user_id'            => Auth::id(),
            ]);

            return redirect()->route('chicken-management')->with([
                'status'  => 'success',
                'message' => 'Data populasi Ayam berhasil disimpan.'
            ]);
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->all();
            return redirect()->back()->with('status', 'error')->with('message', $errors[0]);
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan data populasi ayam: ' . $e->getMessage());
            return redirect()->route('chicken-management')->with([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan data populasi ayam.'
            ]);
        }
    }

    public function storeHarian(Request $request)
    {
        try {
            $request->validate([
                'dailyBatchName' => 'required|exists:populasi_ayam,id',
                'dailyDate' => 'required|date',
                'sickChicken' => 'required|integer|min:0',
                'deadChicken' => 'required|integer|min:0',
            ]);

            $populasi = PopulasiAyam::where('id', $request->dailyBatchName)
            ->where('user_id', Auth::id())
            ->firstOrFail();

            $totalMatiSebelumnya = HarianAyam::where('id_populasi', $populasi->id)->sum('jumlah_ayam_mati');
            $totalSakitSebelumnya = HarianAyam::where('id_populasi', $populasi->id)->sum('jumlah_ayam_sakit');

            $totalMatiBaru = $totalMatiSebelumnya + $request->deadChicken;
            $totalSakitBaru = $totalSakitSebelumnya + $request->sickChicken;
            $totalKeseluruhan = $totalMatiBaru + $totalSakitBaru;

            if ($totalKeseluruhan > $populasi->jumlah_ayam_masuk) {
                return redirect()->back()->with(
                    'error',
                    'Jumlah ayam sakit dan mati melebihi jumlah ayam dalam batch ini.',
                );
            }

            HarianAyam::create([
                'id_populasi' => $populasi->id,
                'nama_batch' => $populasi->nama_batch,
                'tanggal_input' => $request->dailyDate,
                'jumlah_ayam_sakit' => $request->sickChicken,
                'jumlah_ayam_mati' => $request->deadChicken,
                'user_id'            => Auth::id(),
            ]);

            if ($totalMatiBaru == $populasi->jumlah_ayam_masuk) {
                $populasi->update(['status_ayam' => 'Sudah Panen']);
            }

            return redirect()->route('chicken-management')->with([
                'status'  => 'success',
                'message' => 'Data Harian Ayam berhasil disimpan.'
            ]);
        
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan data harian ayam: ' . $e->getMessage());
            return redirect()->route('chicken-management')->with([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan data harian ayam.'
            ]);
        }
        
    }

    public function destroyPopulasi($id)
    {
        try {
            $populasi = PopulasiAyam::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();
            $populasi->delete();

            return response()->json(['success' => true, 'message' => 'Data berhasil dihapus.']);
        } catch (\Exception $e) {
            Log::error('Gagal menghapus data populasi: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menghapus data.'], 500);
        }
    }

    public function destroyHarian($id)
    {
        try {
            $harian = HarianAyam::where('id', $id)
                ->whereHas('populasiAyam', function($query) {
                    $query->where('user_id', Auth::id());
                })
                ->firstOrFail();
            $harian->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'Data Harian Ayam berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal menghapus data harian ayam: ' . $e->getMessage());
    
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data harian ayam.'
            ], 500);
        }
    }

    public function updatePopulasi(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'kandang_id' => 'required|exists:kandang_ayam,id',
                'batchCodeSuffix' => 'required|alpha_num|size:3|unique:populasi_ayam,kode_batch',
                'batchName' => 'required|string|max:255',
                'docDate' => 'required|date',
                'chickenQuantity' => 'required|integer|min:1',
            ], [
                'kandang_id.required' => 'Kandang harus dipilih.',
                'kandang_id.exists' => 'Kandang yang dipilih tidak valid.',
                'batchCodeSuffix.required' => 'Kode populasi harus diisi.',
                'batchCodeSuffix.alpha_num' => 'Kode populasi hanya boleh berisi huruf dan angka.',
                'batchCodeSuffix.size' => 'Kode populasi harus terdiri dari 3 karakter.',
                'batchCodeSuffix.unique' => 'Kode populasi ini sudah digunakan, silakan gunakan kode lain.',
                'batchName.required' => 'Nama populasi harus diisi.',
                'batchName.max' => 'Nama populasi maksimal 255 karakter.',
                'docDate.required' => 'Tanggal DOC harus diisi.',
                'docDate.date' => 'Format tanggal DOC tidak valid.',
                'docDate.before_or_equal' => 'Tanggal DOC tidak boleh lebih dari hari ini.',
                'chickenQuantity.required' => 'Jumlah ayam masuk harus diisi.',
                'chickenQuantity.integer' => 'Jumlah ayam masuk harus berupa angka.',
                'chickenQuantity.min' => 'Jumlah ayam masuk minimal 1 ekor.',
            ]);

            $populasi = PopulasiAyam::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

            $batchCode = 'POPULASI-' . strtoupper($validated['batchCodeSuffix']);
            $kandang = KandangAyam::where('id', $validated['kandang_id'])
                    ->where('user_id', Auth::id())
                    ->firstOrFail();
                $totalAyamDiKandang = PopulasiAyam::where('kandang_id', $kandang->id)
                    ->where('user_id', Auth::id())
                    ->sum('jumlah_ayam_masuk');

            if ($totalAyamDiKandang + $validated['jumlah_ayam_masuk'] > $kandang->kapasitas) {
                return redirect()->back()->with('error', 'Jumlah ayam melebihi kapasitas kandang.');
            }

            $populasi->update([
                'kode_batch' => $batchCode,
                'nama_batch' => $validated['nama_batch'],
                'tanggal_doc' => $validated['tanggal_doc'],
                'jumlah_ayam_masuk' => $validated['jumlah_ayam_masuk'],
                'status_ayam' => $validated['status_ayam'],
                'kandang_id' => $validated['kandang_id'],
            ]);

            return redirect()->route('chicken-management')->with([
                'status'  => 'success',
                'message' => 'Data populasi Ayam berhasil diperbarui.'
            ]);

        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->all();
            return redirect()->back()->with('status', 'error')->with('message', $errors[0]);
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui data populasi ayam: ' . $e->getMessage());
            return redirect()->route('chicken-management')->with([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui data populasi ayam.'
            ]);
        }
    }
  
    public function updateHarian(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'dailyBatchName' => 'required|exists:populasi_ayam,id',
                'tanggal_input' => 'required|date',
                'jumlah_ayam_sakit' => 'required|integer|min:0',
                'jumlah_ayam_mati' => 'required|integer|min:0',
            ]);

            $harian = HarianAyam::where('id', $id)
                ->whereHas('populasiAyam', function($query) {
                    $query->where('user_id', Auth::id());
                })
                ->firstOrFail();

            $populasi = PopulasiAyam::where('id', $validated['dailyBatchName'])
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $totalAyamDipantau = HarianAyam::where('id_populasi', $populasi->id)->sum('jumlah_ayam_mati')
                                + HarianAyam::where('id_populasi', $populasi->id)->sum('jumlah_ayam_sakit')
                                - $harian->jumlah_ayam_sakit - $harian->jumlah_ayam_mati
                                + $validated['jumlah_ayam_sakit'] + $validated['jumlah_ayam_mati'];

            if ($totalAyamDipantau > $populasi->jumlah_ayam_masuk) {
                return redirect()->back()->with('error', 'Jumlah ayam sakit + mati melebihi jumlah ayam dalam batch ini.');
            }

            $harian->update([
                'id_populasi' => $validated['dailyBatchName'],
                'nama_batch' => $populasi->nama_batch,
                'tanggal_input' => $validated['tanggal_input'],
                'jumlah_ayam_sakit' => $validated['jumlah_ayam_sakit'],
                'jumlah_ayam_mati' => $validated['jumlah_ayam_mati'],
            ]);

            return redirect()->route('chicken-management')->with([
                'status'  => 'success',
                'message' => 'Data harian Ayam berhasil diperbarui.'
            ]);

        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->all();
            return redirect()->back()->with('status', 'error')->with('message', $errors[0]);
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui data harian ayam: ' . $e->getMessage());
            return redirect()->route('chicken-management')->with([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui data harian ayam.'
            ]);
        }
    }

    public function cetak($id)
    {
        try {
            $populasi = PopulasiAyam::with('harianAyam')->findOrFail($id);
            $pdf = Pdf::loadView('cetak.laporan-populasi', compact('populasi'));
            return $pdf->download("Laporan_Manajemen_Ayam_{$populasi->kode_batch}.pdf");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mencetak laporan.');
        }
    }
}   
