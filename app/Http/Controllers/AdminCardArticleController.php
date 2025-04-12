<?php

namespace App\Http\Controllers;

use App\Models\CardArticle;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class AdminCardArticleController extends Controller
{
    public function indexAdminArtikel(Request $request)
    {
        try {
            $artikelPage = $request->get('artikel_page', 1);
            $articles = CardArticle::where('user_id', Auth::id())
                ->withCount('articles')
                ->latest()
                ->paginate(5, ['*'], 'artikel_page', $artikelPage);

            $pendingCount = Article::where('status', 'Tertunda')
                ->where('user_id', Auth::id())
                ->count();
            $approvedCount = Article::where('status', 'Disetujui')
                ->where('user_id', Auth::id())
                ->count();
            $rejectedCount = Article::where('status', 'Ditolak')
                ->where('user_id', Auth::id())
                ->count();
            
            return view('admin.add-card-article', compact('articles', 'pendingCount', 'approvedCount', 'rejectedCount'));
        } catch (\Exception $e) {
            Log::error('Gagal memuat data artikel: ' . $e->getMessage());
            return redirect()->route('admin.add-article')->with([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memuat data artikel.',
            ]);
        }
    }

    public function storeAdminArtikel(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', 
            ], [
                'title.required' => 'Judul grup artikel harus diisi.',
                'title.max' => 'Judul grup artikel maksimal 255 karakter.',
                'description.required' => 'Deskripsi grup artikel harus diisi.',
                'image.required' => 'Gambar grup artikel harus diunggah.',
                'image.image' => 'File harus berupa gambar.',
                'image.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif.',
                'image.max' => 'Ukuran gambar maksimal 2MB.'
            ]);

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('card_articles', 'public');
            }

            $cardArticle = CardArticle::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'image' => $imagePath,
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('admin.add-article')->with([
                'status' => 'success',
                'message' => 'Artikel grup berhasil ditambahkan!',
            ]);
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan artikel: ' . $e->getMessage());

            return redirect()->route('admin.add-article')->with([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan artikel.',
            ]);
        }
    }

    public function updateAdminArtikel(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
            ], [
                'title.required' => 'Judul grup artikel harus diisi.',
                'title.max' => 'Judul grup artikel maksimal 255 karakter.',
                'description.required' => 'Deskripsi grup artikel harus diisi.',
                'image.image' => 'File harus berupa gambar.',
                'image.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif.',
                'image.max' => 'Ukuran gambar maksimal 2MB.'
            ]);

            $card = CardArticle::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

            $imagePath = $card->image;
            
            if ($request->hasFile('image')) {
                if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                    Log::info('Gambar lama berhasil dihapus: ' . $imagePath);
                }
                
                $newImagePath = $request->file('image')->store('card_articles', 'public');
                Log::info('Gambar baru berhasil disimpan: ' . $newImagePath);
                $imagePath = $newImagePath;
            }

            $card->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'image' => $imagePath,
            ]);

            return redirect()->route('add-article')->with([
                'status' => 'success',
                'message' => 'Artikel grup berhasil diperbarui!',
            ]);
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (ModelNotFoundException $e) {
            return redirect()->route('admin.add-article')->with([
                'status' => 'error',
                'message' => 'Artikel grup tidak ditemukan.',
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui artikel grup: ' . $e->getMessage());

            return redirect()->route('admin.add-article')->with([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui artikel grup.',
            ]);
        }
    }

    public function deleteAdminArtikel($id)
    {
        try {
            $cardArticle = CardArticle::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            if ($cardArticle->image) {
                Storage::disk('public')->delete($cardArticle->image);
            }
            $cardArticle->delete();

            return response()->json(['success' => true, 'message' => 'Artikel berhasil dihapus.']);
        } catch (\Exception $e) {
            Log::error('Gagal menghapus artikel: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menghapus artikel.'], 500);
        }
    }
}
