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
                $sisaKapasitas = $kandang->kapasitas - $totalAyamDiKandang;
                
                return redirect()->back()->with([
                    'status' => 'error',
                    'message' => 'Jumlah ayam yang ingin ditambahkan melebihi kapasitas kandang. Kapasitas tersisa: ' . 
                                $sisaKapasitas . ' ekor, sedangkan Anda mencoba menambahkan ' . 
                                $request->chickenQuantity . ' ekor.'
                ]);
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
            ], [
                'dailyBatchName.required' => 'Populasi ayam harus dipilih.',
                'dailyBatchName.exists' => 'Populasi ayam yang dipilih tidak valid.',
                'dailyDate.required' => 'Tanggal input harus diisi.',
                'dailyDate.date' => 'Format tanggal tidak valid.',
                'sickChicken.required' => 'Jumlah ayam sakit harus diisi.',
                'sickChicken.integer' => 'Jumlah ayam sakit harus berupa angka.',
                'sickChicken.min' => 'Jumlah ayam sakit tidak boleh negatif.',
                'deadChicken.required' => 'Jumlah ayam mati harus diisi.',
                'deadChicken.integer' => 'Jumlah ayam mati harus berupa angka.',
                'deadChicken.min' => 'Jumlah ayam mati tidak boleh negatif.',
            ]);

            $populasi = PopulasiAyam::where('id', $request->dailyBatchName)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $totalMatiSebelumnya = HarianAyam::where('id_populasi', $populasi->id)->sum('jumlah_ayam_mati');
            $totalSakitSebelumnya = HarianAyam::where('id_populasi', $populasi->id)->sum('jumlah_ayam_sakit');

            $newSick = (int)$request->sickChicken;
            $newDead = (int)$request->deadChicken;

            $totalMatiBaru = $totalMatiSebelumnya + $newDead;
            $totalSakitBaru = $totalSakitSebelumnya + $newSick;
            $totalKeseluruhan = $totalMatiBaru + $totalSakitBaru;

            Log::info('Validation harian ayam:', [
                'populasi_id' => $populasi->id,
                'jumlah_ayam_masuk' => $populasi->jumlah_ayam_masuk,
                'total_mati_sebelumnya' => $totalMatiSebelumnya,
                'total_sakit_sebelumnya' => $totalSakitSebelumnya,
                'new_sick' => $newSick,
                'new_dead' => $newDead,
                'total_mati_baru' => $totalMatiBaru,
                'total_sakit_baru' => $totalSakitBaru,
                'total_keseluruhan' => $totalKeseluruhan
            ]);

            if ($totalKeseluruhan > $populasi->jumlah_ayam_masuk) {
                $availableCount = $populasi->jumlah_ayam_masuk - $totalMatiSebelumnya - $totalSakitSebelumnya;
                
                return redirect()->back()->with([
                    'status' => 'error',
                    'message' => "Jumlah ayam sakit dan mati melebihi jumlah ayam tersedia. Jumlah tersedia: {$availableCount} ekor, sedangkan Anda mencoba menambahkan {$newSick} sakit dan {$newDead} mati (total: " . ($newSick + $newDead) . " ekor)."
                ]);
            }

            HarianAyam::create([
                'id_populasi' => $populasi->id,
                'nama_batch' => $populasi->nama_batch,
                'tanggal_input' => $request->dailyDate,
                'jumlah_ayam_sakit' => $newSick,
                'jumlah_ayam_mati' => $newDead,
                'user_id' => Auth::id(),
            ]);

            if ($totalMatiBaru == $populasi->jumlah_ayam_masuk) {
                $populasi->update(['status_ayam' => 'Sudah Panen']);
            }

            return redirect()->route('chicken-management')->with([
                'status' => 'success',
                'message' => 'Data Harian Ayam berhasil disimpan.'
            ]);
        
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan data harian ayam: ' . $e->getMessage());
            return redirect()->route('chicken-management')->with([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan data harian ayam: ' . $e->getMessage()
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
            $populasi = PopulasiAyam::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();
            
            $validated = $request->validate([
                'kandang_id' => 'required|exists:kandang_ayam,id',
                'batchCodeSuffix' => 'required|alpha_num|size:3|unique:populasi_ayam,kode_batch,'.$id.',id',
                'nama_batch' => 'required|string|max:255',
                'tanggal_doc' => 'required|date',
                'jumlah_ayam_masuk' => 'required|integer|min:1',
                'status_ayam' => 'required|in:Proses,Siap Panen,Sudah Panen',
            ], [
                'kandang_id.required' => 'Kandang harus dipilih.',
                'kandang_id.exists' => 'Kandang yang dipilih tidak valid.',
                'batchCodeSuffix.required' => 'Kode populasi harus diisi.',
                'batchCodeSuffix.alpha_num' => 'Kode populasi hanya boleh berisi huruf dan angka.',
                'batchCodeSuffix.size' => 'Kode populasi harus terdiri dari 3 karakter.',
                'batchCodeSuffix.unique' => 'Kode populasi ini sudah digunakan, silakan gunakan kode lain.',
                'nama_batch.required' => 'Nama populasi harus diisi.',
                'nama_batch.max' => 'Nama populasi maksimal 255 karakter.',
                'tanggal_doc.required' => 'Tanggal DOC harus diisi.',
                'tanggal_doc.date' => 'Format tanggal DOC tidak valid.',
                'jumlah_ayam_masuk.required' => 'Jumlah ayam masuk harus diisi.',
                'jumlah_ayam_masuk.integer' => 'Jumlah ayam masuk harus berupa angka.',
                'jumlah_ayam_masuk.min' => 'Jumlah ayam masuk minimal 1 ekor.',
                'status_ayam.required' => 'Status ayam harus dipilih.',
                'status_ayam.in' => 'Status ayam tidak valid.',
            ]);

            $kandang = KandangAyam::where('id', $validated['kandang_id'])
                    ->where('user_id', Auth::id())
                    ->firstOrFail();
            
            $changingCage = $populasi->kandang_id != $validated['kandang_id'];
            $totalAyamDiKandangBaru = PopulasiAyam::where('kandang_id', $validated['kandang_id'])
                    ->where('user_id', Auth::id())
                    ->where('id', '!=', $id)
                    ->sum('jumlah_ayam_masuk');
            
            Log::info('Perhitungan kapasitas kandang:', [
                'kandang_id' => $validated['kandang_id'],
                'kapasitas_kandang' => $kandang->kapasitas,
                'total_populasi_lain' => $totalAyamDiKandangBaru,
                'populasi_yang_diedit_id' => $id,
                'jumlah_ayam_masuk_lama' => $populasi->jumlah_ayam_masuk,
                'jumlah_ayam_masuk_baru' => $validated['jumlah_ayam_masuk'],
                'sisa_kapasitas' => $kandang->kapasitas - $totalAyamDiKandangBaru
            ]);
            
            if ($totalAyamDiKandangBaru + $validated['jumlah_ayam_masuk'] > $kandang->kapasitas) {
                $sisaKapasitas = $kandang->kapasitas - $totalAyamDiKandangBaru;
                
                return redirect()->back()->with([
                    'status' => 'error',
                    'message' => 'Jumlah ayam yang ingin ditambahkan melebihi kapasitas kandang. Kapasitas tersisa: ' . 
                            $sisaKapasitas . ' ekor dari ' . $kandang->kapasitas . ' total kapasitas, sedangkan Anda mencoba menambahkan ' . 
                            $validated['jumlah_ayam_masuk'] . ' ekor.'
                ]);
            }

            $batchCode = 'POPULASI-' . strtoupper($validated['batchCodeSuffix']);

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
                'message' => 'Terjadi kesalahan saat memperbarui data populasi ayam: ' . $e->getMessage()
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
        ], [
            'dailyBatchName.required' => 'Populasi ayam harus dipilih.',
            'dailyBatchName.exists' => 'Populasi ayam yang dipilih tidak valid.',
            'tanggal_input.required' => 'Tanggal input harus diisi.',
            'tanggal_input.date' => 'Format tanggal tidak valid.',
            'jumlah_ayam_sakit.required' => 'Jumlah ayam sakit harus diisi.',
            'jumlah_ayam_sakit.integer' => 'Jumlah ayam sakit harus berupa angka.',
            'jumlah_ayam_sakit.min' => 'Jumlah ayam sakit tidak boleh negatif.',
            'jumlah_ayam_mati.required' => 'Jumlah ayam mati harus diisi.',
            'jumlah_ayam_mati.integer' => 'Jumlah ayam mati harus berupa angka.',
            'jumlah_ayam_mati.min' => 'Jumlah ayam mati tidak boleh negatif.',
        ]);

        $harian = HarianAyam::where('id', $id)
            ->whereHas('populasiAyam', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->firstOrFail();

        $populasi = PopulasiAyam::where('id', $validated['dailyBatchName'])
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $totalMatiDariRecordLain = HarianAyam::where('id_populasi', $populasi->id)
            ->where('id', '!=', $id)
            ->sum('jumlah_ayam_mati');
            
        $totalSakitDariRecordLain = HarianAyam::where('id_populasi', $populasi->id)
            ->where('id', '!=', $id)
            ->sum('jumlah_ayam_sakit');

        $newSick = (int)$validated['jumlah_ayam_sakit'];
        $newDead = (int)$validated['jumlah_ayam_mati'];
        
        $totalTerpantau = $totalMatiDariRecordLain + $totalSakitDariRecordLain + $newSick + $newDead;

        Log::info('Update harian ayam calculation:', [
            'record_id' => $id,
            'populasi_id' => $populasi->id,
            'total_population' => $populasi->jumlah_ayam_masuk,
            'total_mati_record_lain' => $totalMatiDariRecordLain,
            'total_sakit_record_lain' => $totalSakitDariRecordLain,
            'new_sick' => $newSick,
            'new_dead' => $newDead,
            'total_terpantau' => $totalTerpantau
        ]);

        if ($totalTerpantau > $populasi->jumlah_ayam_masuk) {
            $availableCount = $populasi->jumlah_ayam_masuk - $totalMatiDariRecordLain - $totalSakitDariRecordLain;
            
            return redirect()->back()->with([
                'status' => 'error',
                'message' => "Jumlah ayam sakit dan mati melebihi jumlah ayam tersedia. Jumlah tersedia: {$availableCount} ekor, sedangkan Anda mencoba mencatat {$newSick} sakit dan {$newDead} mati (total: " . ($newSick + $newDead) . " ekor)."
            ]);
        }

        $harian->update([
            'id_populasi' => $validated['dailyBatchName'],
            'nama_batch' => $populasi->nama_batch,
            'tanggal_input' => $validated['tanggal_input'],
            'jumlah_ayam_sakit' => $newSick,
            'jumlah_ayam_mati' => $newDead,
        ]);

        $totalMatiSetelahUpdate = HarianAyam::where('id_populasi', $populasi->id)->sum('jumlah_ayam_mati');
        if ($totalMatiSetelahUpdate == $populasi->jumlah_ayam_masuk) {
            $populasi->update(['status_ayam' => 'Sudah Panen']);
        }

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
            'message' => 'Terjadi kesalahan saat memperbarui data harian ayam: ' . $e->getMessage()
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

    public function getAvailableChickenCount($batchId, $recordId = null)
    {
        try {
            $populasi = PopulasiAyam::where('id', $batchId)
                ->where('user_id', Auth::id())
                ->firstOrFail();
            
            $harianQuery = HarianAyam::where('id_populasi', $batchId);
            if ($recordId) {
                $harianQuery->where('id', '!=', $recordId);
            }
            
            $totalSick = $harianQuery->sum('jumlah_ayam_sakit');
            $totalDead = $harianQuery->sum('jumlah_ayam_mati');
            
            $availableCount = $populasi->jumlah_ayam_masuk - $totalSick - $totalDead;
            $availableCount = max(0, $availableCount); 
            
            return response()->json([
                'success' => true,
                'available_count' => $availableCount,
                'total_population' => $populasi->jumlah_ayam_masuk,
                'recorded_sick' => $totalSick,
                'recorded_dead' => $totalDead
            ]);
        } catch (\Exception $e) {
            Log::error('Error retrieving chicken count: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getHarianRecord($id)
    {
        try {
            $harian = HarianAyam::where('id', $id)
                ->whereHas('populasiAyam', function($query) {
                    $query->where('user_id', Auth::id());
                })
                ->firstOrFail();
                
            return response()->json([
                'success' => true,
                'data' => $harian
            ]);
        } catch (\Exception $e) {
            Log::error('Error retrieving harian record: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving record data: ' . $e->getMessage()
            ], 500);
        }
    }
}   
