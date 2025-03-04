<?php

namespace App\Http\Controllers;

use App\Models\CardArticle;  // Model untuk grup artikel
use App\Models\Article;      // Model untuk artikel
use App\Models\Tag;          // Model untuk tag
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserArticleController extends Controller
{
    // Menampilkan halaman form untuk menambah artikel
    public function indexUserArtikel(Request $request)
    {
        try {
            // Tangkap parameter query string untuk paginasi
            $articlePage = $request->get('article_page', 1);

            // Paginasi dengan appends untuk menjaga parameter query saat halaman berubah
            $articles = Article::latest()->paginate(4, ['*'], 'article_page', $articlePage);

            // Ambil semua grup artikel (CardArticle) dan tags untuk ditampilkan pada form
            $cardArticles = CardArticle::all();
            $tags = Tag::all();

            // Menambahkan parameter yang sama dengan query untuk pagination
            $articles->appends(['article_page' => $articlePage]);

            return view('add-article-detail', compact('articles', 'cardArticles', 'tags',));
        } catch (\Exception $e) {
            Log::error('Gagal memuat data artikel: ' . $e->getMessage());

            return redirect()->route('add-article-detail')->with([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memuat data artikel.',
            ]);
        }
    }

    // Menyimpan artikel dan tag terkait
    public function storeUserArtikel(Request $request)
    {
        try {
            // Validasi inputan
            $validated = $request->validate([
                'card_id' => 'required|exists:card_articles,id', // Pilih grup artikel
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'status' => 'required|string|in:Tertunda,Disetujui,Ditolak',
                'tags' => 'nullable|array', // Untuk multiple tags
                'tags.*' => 'exists:tags,id', 
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('articles', 'public');
            }

            // Simpan artikel
            $article = Article::create([
                'card_id' => $validated['card_id'],
                'title' => $validated['title'],
                'description' => $validated['description'],
                'status' => $validated['status'],
                'image' => $imagePath,
            ]);

            // Jika ada tag yang dipilih, simpan relasi antara artikel dan tag
            if (!empty($validated['tags'])) {
                $article->tags()->attach($validated['tags']);
            }

            return redirect()->route('add-article-detail')->with([
                'status' => 'success',
                'message' => 'Artikel berhasil dibuat!',
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan artikel: ' . $e->getMessage());

            return redirect()->route('add-article-detail')->with([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan artikel.',
            ]);
        }
    }

    // Mengupdate artikel dan tag terkait
    public function updateUserArtikel(Request $request, $id)
{
    try {
        $validated = $request->validate([
            'card_id' => 'required|exists:card_articles,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string|in:Tertunda,Disetujui,Ditolak',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $article = Article::findOrFail($id);

        // Pastikan hanya update artikel tanpa merubah card title
        $imagePath = $article->image;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('articles', 'public');
        }

        $article->update([
            'card_id' => $validated['card_id'],  // Hanya merubah card_id
            'title' => $validated['title'],  // Mengubah title artikel
            'description' => $validated['description'],
            'status' => $validated['status'],
            'image' => $imagePath,
        ]);

        // Update tags
        if (!empty($validated['tags'])) {
            $article->tags()->sync($validated['tags']);
        }

        return redirect()->route('add-article-detail')->with([
            'status' => 'success',
            'message' => 'Artikel berhasil diperbarui!',
        ]);
    } catch (\Exception $e) {
        Log::error('Gagal memperbarui artikel: ' . $e->getMessage());
        return redirect()->route('add-article-detail')->with([
            'status' => 'error',
            'message' => 'Terjadi kesalahan saat memperbarui artikel.',
        ]);
    }
}

    /**
     * Menghapus artikel dan relasi terkait.
     */
    public function deleteUserArtikel($id)
    {
        try {
            $article = Article::findOrFail($id);

            // Hapus artikel dan relasi dengan tags
            $article->tags()->detach();
            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }   
            $article->delete();

            return response()->json(['success' => true, 'message' => 'Artikel berhasil dihapus.']);
        } catch (\Exception $e) {
            Log::error('Gagal menghapus artikel: ' . $e->getMessage());

            return response()->json(['success' => false, 'message' => 'Gagal menghapus artikel.'], 500);
        }
    }
}