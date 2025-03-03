<?php

namespace App\Http\Controllers;

use App\Models\CardArticle;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminCardArticleController extends Controller
{
    public function indexAdminArtikel(Request $request)
    {
        try {
            $artikelPage = $request->get('artikel_page', 1);
            $articles = CardArticle::latest()->paginate(5, ['*'], 'artikel_page', $artikelPage);

            $pendingCount = Article::where('status', 'Tertunda')->count();
            $approvedCount = Article::where('status', 'Disetujui')->count();
            $rejectedCount = Article::where('status', 'Ditolak')->count();
            
            return view('admin.add-card-article', compact('articles', 'pendingCount', 'approvedCount', 'rejectedCount'));
        } catch (\Exception $e) {
            Log::error('Gagal memuat data artikel: ' . $e->getMessage());
            return redirect()->route('add-article')->with([
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
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Gambar opsional
        ]);

        // Simpan gambar jika ada
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('card_articles', 'public');
            Log::info('Gambar berhasil disimpan di: ' . $imagePath); 
        }

        // Simpan artikel ke dalam 'card_articles'
        $cardArticle = CardArticle::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'image' => $imagePath,
        ]);

        return redirect()->route('add-article')->with([
            'status' => 'success',
            'message' => 'Artikel grup berhasil ditambahkan!',
        ]);
    } catch (\Exception $e) {
        Log::error('Gagal menyimpan artikel: ' . $e->getMessage());

        return redirect()->route('add-article')->with([
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
            'description' => 'nullable|string',
        ]);

        $card = CardArticle::findOrFail($id);
        $card->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
        ]);

        return redirect()->route('add-article')->with([
            'status' => 'success',
            'message' => 'Card berhasil diperbarui!',
        ]);
    } catch (\Exception $e) {
        Log::error('Gagal memperbarui Card: ' . $e->getMessage());

        return redirect()->route('add-article')->with([
            'status' => 'error',
            'message' => 'Terjadi kesalahan saat memperbarui Card.',
        ]);
    }
}

    public function deleteAdminArtikel($id)
    {
        try {
            $cardArticle = CardArticle::findOrFail($id);
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
