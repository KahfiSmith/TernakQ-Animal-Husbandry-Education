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
    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:Tertunda,Disetujui,Ditolak',
            ]);

            $article = Article::findOrFail($id);
            $article->update(['status' => $request->status]);

            return redirect()->route('admin.article-management')
                ->with('status', 'success')
                ->with('message', 'Status artikel berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Gagal mengubah status artikel: ' . $e->getMessage());
            return redirect()->route('admin.article-management')
                ->with('status', 'error')
                ->with('message', 'Terjadi kesalahan saat memperbarui status artikel.');
        }
    }

    public function editArticle($id)
{
    try {
        $article = Article::findOrFail($id);
        return view('admin.article-management-edit', compact('article'));
    } catch (\Exception $e) {
        Log::error('Gagal memuat artikel untuk edit: ' . $e->getMessage());
        return redirect()->route('admin.article-management-edit')
            ->with('status', 'error')
            ->with('message', 'Terjadi kesalahan saat memuat artikel.');
    }
}

}
