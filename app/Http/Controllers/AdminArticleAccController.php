<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminArticleAccController extends Controller
{
    /**
     * Menampilkan daftar artikel untuk admin dengan pagination.
     */
    public function indexAdminArticle(Request $request)
    {
        try {
            // 🔄 Paginasi untuk data artikel (10 per halaman)
            $articlePage = $request->get('article_page', 1);
            $articles = Article::latest()->paginate(10, ['*'], 'article_page', $articlePage);

            return view('admin.article-management', compact('articles'));
        } catch (\Exception $e) {
            Log::error('Gagal memuat data artikel: ' . $e->getMessage());

            return redirect()->route('admin.article-management')->with([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memuat data artikel.',
            ]);
        }
    }

    /**
     * Mengubah status artikel.
     */

    public function editArticle($id)
{
    try {
        $article = Article::with(['subArticles' => function ($query) {
            $query->orderBy('order_number', 'asc'); 
        }])->findOrFail($id);
        return view('admin.article-management-edit', compact('article'));
    } catch (\Exception $e) {
        Log::error('Gagal memuat artikel untuk edit: ' . $e->getMessage());
        return redirect()->route('admin.article-management-edit')
            ->with('status', 'error')
            ->with('message', 'Terjadi kesalahan saat memuat artikel.');
    }
}

public function updateArticle(Request $request, $id)
{
    try {
        $validated = $request->validate([
            'catatan' => 'nullable|string',
            'status'  => 'required|in:Tertunda,Disetujui,Ditolak',
        ]);

        $article = Article::findOrFail($id);
        $article->update($validated);

        return redirect()->route('admin.article-management')->with([
            'status'  => 'success',
            'message' => 'Artikel berhasil diperbarui!',
        ]);
    } catch (\Exception $e) {
        Log::error('Gagal memperbarui artikel: ' . $e->getMessage());
        return redirect()->route('admin.article-management')->with([
            'status'  => 'error',
            'message' => 'Terjadi kesalahan saat memperbarui artikel.',
        ]);
    }
}

}
