<?php

namespace App\Http\Controllers;

use App\Models\SubArticle;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserSubArticleController extends Controller
{
    /**
     * Menampilkan daftar sub-artikel dengan pagination.
     * Selain itu, mengirimkan daftar artikel induk untuk dropdown.
     */
    public function indexUserArtikel(Request $request)
{
    try {
        // Ambil daftar artikel induk untuk dropdown
        $articles = Article::all();
        $selectedArticle = null;
        $subArticles = collect(); // Default kosong jika belum ada artikel dipilih

        $articleId = $request->query('article_id'); // Ambil dari query string
        if ($articleId) {
            $selectedArticle = Article::with(['subArticles' => function ($query) {
                $query->orderBy('order_number', 'asc');
            }])->find($articleId);

            if ($selectedArticle) {
                $subArticles = $selectedArticle->subArticles;
            }
        }

        return view('add-article-sub', compact('subArticles', 'articles', 'selectedArticle'));
    } catch (\Exception $e) {
        Log::error('Gagal memuat sub-artikel: ' . $e->getMessage());

        return redirect()->route('add-article-sub')->with([
            'status'  => 'error',
            'message' => 'Terjadi kesalahan saat memuat data sub-artikel.',
        ]);
    }
}



    /**
     * Menyimpan sub-artikel baru.
     * Pastikan form mengirimkan: article_id, title, content, order_number, dan image (opsional).
     */
    
    public function storeMultipleSubArticles(Request $request)
{
    try {
        $validated = $request->validate([
            'article_id'                  => 'required|exists:articles,id',
            'sub_articles'                => 'required|array|min:1',
            'sub_articles.*.title'        => 'required|string|max:255',
            'sub_articles.*.content'      => 'required|string',
            'sub_articles.*.order_number' => 'required|integer|min:1',
            'sub_articles.*.image'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $subArticlesData = [];
        foreach ($validated['sub_articles'] as $subArticle) {
            $imagePath = null;
            if (isset($subArticle['image'])) {
                $imagePath = $subArticle['image']->store('sub_articles', 'public');
            }

            $subArticlesData[] = [
                'article_id'   => $validated['article_id'],
                'title'        => $subArticle['title'],
                'content'      => $subArticle['content'],
                'order_number' => $subArticle['order_number'],
                'image'        => $imagePath,
                'created_at'   => now(),
                'updated_at'   => now(),
            ];
        }

        SubArticle::insert($subArticlesData);

        return redirect()->route('add-article-sub')->with([
            'status'  => 'success',
            'message' => 'Semua sub-artikel berhasil disimpan!',
        ]);
    } catch (\Exception $e) {
        Log::error('Gagal menyimpan sub-artikel: ' . $e->getMessage());
        return redirect()->route('add-article-sub')->with([
            'status'  => 'error',
            'message' => 'Terjadi kesalahan saat menyimpan sub-artikel.',
        ]);
    }
}


    /**
     * Mengupdate sub-artikel yang sudah ada.
     */
    public function updateUserArtikel(Request $request, $id)
    {
        try {
            $subArticle = SubArticle::findOrFail($id);

            $validated = $request->validate([
                'article_id'   => 'required|exists:articles,id',
                'title'        => 'required|string|max:255',
                'content'      => 'required|string',
                'order_number' => 'required|integer|min:1',
                'image'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $imagePath = $subArticle->image;
            if ($request->hasFile('image')) {
                // Hapus gambar lama jika ada
                if ($subArticle->image) {
                    Storage::disk('public')->delete($subArticle->image);
                }

                $imagePath = $request->file('image')->store('sub_articles', 'public');
            }

            $subArticle->update([
                'article_id'   => $validated['article_id'],
                'title'        => $validated['title'],
                'content'      => $validated['content'],
                'order_number' => $validated['order_number'],
                'image'        => $imagePath,
            ]);

            return redirect()->route('add-article-sub')->with([
                'status'  => 'success',
                'message' => 'Sub-artikel berhasil diperbarui!',
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui sub-artikel: ' . $e->getMessage());
            return redirect()->route('add-article-sub')->with([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui sub-artikel.',
            ]);
        }
    }

    /**
     * Menghapus sub-artikel.
     */
    public function deleteUserArtikel($id)
    {
        try {
            $subArticle = SubArticle::findOrFail($id);
            if ($subArticle->image) {
                Storage::disk('public')->delete($subArticle->image);
            }
            $subArticle->delete();

            return response()->json(['success' => true, 'message' => 'Sub-artikel berhasil dihapus.']);
        } catch (\Exception $e) {
            Log::error('Gagal menghapus sub-artikel: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menghapus sub-artikel.'], 500);
        }
    }
}
