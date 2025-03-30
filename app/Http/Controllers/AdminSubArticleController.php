<?php

namespace App\Http\Controllers;

use App\Models\SubArticle;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class AdminSubArticleController extends Controller
{
    
    public function indexAdminArtikel(Request $request)
{
    try {
        $subArticle = null;  // Defaultnya null jika tidak ada sub artikel yang sedang diedit
        // Ambil daftar artikel induk untuk dropdown
        $articles = Article::where('user_id', Auth::id())->get();
        $selectedArticle = null;  // Artikel yang dipilih untuk menampilkan sub artikel
        $subArticles = collect(); // Koleksi sub artikel yang kosong

        // Ambil artikel_id dari query string
        $articleId = $request->query('article_id');
        if ($articleId) {
            // Pastikan artikel yang dipilih milik user yang sedang login
            $selectedArticle = Article::with(['subArticles' => function ($query) {
                $query->orderBy('order_number', 'asc')
                      ->orderBy('id', 'desc');  // Menampilkan sub artikel dengan urutan yang benar
            }])->where('user_id', Auth::id())->find($articleId);

            if ($selectedArticle) {
                $subArticles = $selectedArticle->subArticles;
            }
        }

        // Jika ada permintaan untuk mengedit sub artikel
        $editId = $request->query('edit_id'); // Jika ada ID untuk mengedit
        if ($editId) {
            $subArticle = SubArticle::where('id', $editId)
                ->whereHas('article', function ($query) {
                    $query->where('user_id', Auth::id());
                })
                ->first();
        }

        // Pastikan kita hanya mempass data yang ada
        return view('admin.add-article-sub', compact('subArticles', 'articles', 'selectedArticle', 'subArticle'));
    } catch (\Exception $e) {
        Log::error('Gagal memuat sub-artikel: ' . $e->getMessage());

        return redirect()->route('admin.add-article-sub')->with([
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
        ], [
            'sub_articles.*.order_number.min' => 'Order number minimal 1.'  // Pesan error custom untuk order_number
        ]);

        $article = Article::where('id', $validated['article_id'])
                ->where('user_id', Auth::id())
                ->firstOrFail();

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
                'user_id'      => Auth::id(),
                'created_at'   => now(),
                'updated_at'   => now(),
            ];
        }

        SubArticle::insert($subArticlesData);

        return redirect()->route('admin.add-article-sub')->with([
            'status'  => 'success',
            'message' => 'Semua sub-artikel berhasil disimpan!',
        ]);
    } catch (ValidationException $e) {
        // Ambil pesan error pertama
        $errors = $e->validator->errors()->all();
        return redirect()->back()->with('status', 'error')->with('message', $errors[0]);
    } catch (\Exception $e) {
        Log::error('Gagal menyimpan sub-artikel: ' . $e->getMessage());
        return redirect()->route('admin.add-article-sub')->with([
            'status'  => 'error',
            'message' => 'Terjadi kesalahan saat menyimpan sub-artikel.',
        ]);
    }
}


    /**
     * Mengupdate sub-artikel yang sudah ada.
     */
    public function updateAdminArtikel(Request $request, $id)
{
    try {
        $subArticle = SubArticle::where('id', $id)
            ->whereHas('article', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->firstOrFail();

        $validated = $request->validate([
            'article_id'   => 'required|exists:articles,id',
            'sub_articles' => 'required|array',
            'sub_articles.*.title' => 'required|string|max:255',
            'sub_articles.*.content' => 'required|string',
            'sub_articles.*.order_number' => 'required|integer|min:1',
            'sub_articles.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // ...
        ]);

        // Hapus gambar jika remove_image bernilai '1'
        $removeImage = $request->input('sub_articles.0.remove_image');
        $imagePath = $subArticle->image;

        if ($removeImage === '1') {
            // Hapus gambar lama jika ada
            if ($subArticle->image) {
                Storage::disk('public')->delete($subArticle->image);
            }
            $imagePath = null;
        } elseif ($request->hasFile('sub_articles.0.image')) {
            // Upload gambar baru
            if ($subArticle->image) {
                Storage::disk('public')->delete($subArticle->image);
            }
            $imagePath = $request->file('sub_articles.0.image')->store('sub_articles', 'public');
        }

        // Update sub artikel
        $subArticle->update([
            'article_id'   => $validated['article_id'],
            'title'        => $validated['sub_articles'][0]['title'],
            'content'      => $validated['sub_articles'][0]['content'],
            'order_number' => $validated['sub_articles'][0]['order_number'],
            'image'        => $imagePath,
        ]);

        return redirect()->route('admin.add-article-sub')->with([
            'status'  => 'success',
            'message' => 'Sub-artikel berhasil diperbarui!',
        ]);
    } catch (ValidationException $e) {
        $errors = $e->validator->errors();
        return redirect()->back()->with('status', 'error')->with('message', $errors->first());
    } catch (\Exception $e) {
        Log::error('Gagal memperbarui sub-artikel: ' . $e->getMessage());
        return redirect()->route('admin.add-article-sub')->with([
            'status'  => 'error',
            'message' => 'Terjadi kesalahan saat memperbarui sub-artikel.',
        ]);
    }
}

    public function editAdminArtikel($id)
{
    try {
        // Mengambil sub artikel berdasarkan ID dan memastikan itu milik user yang sedang login
        $subArticle = SubArticle::where('id', $id)
            ->whereHas('article', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->firstOrFail();

        // Ambil artikel yang terkait dengan sub artikel untuk di-passing ke view
        $selectedArticle = $subArticle->article;

        // Ambil semua artikel untuk dropdown pilihan artikel induk
        $articles = Article::where('user_id', Auth::id())->get();

        // Kirim data ke view
        return view('admin.add-article-sub', compact('subArticle', 'selectedArticle', 'articles'));
    } catch (\Exception $e) {
        return redirect()->route('admin.add-article-sub')->with([
            'status' => 'error',
            'message' => 'Sub-artikel tidak ditemukan atau tidak dapat diakses.',
        ]);
    }
}

public function deleteUserArtikel($id)
{
    try {
        $subArticle = SubArticle::where('id', $id)
            ->whereHas('article', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->firstOrFail();

        if ($subArticle->image) {
            Storage::disk('public')->delete($subArticle->image);
        }

        $subArticle->delete();

        return redirect()->route('add-article-sub')->with([
            'status'  => 'success',
            'message' => 'Sub-artikel berhasil dihapus!',
        ]);
    } catch (\Exception $e) {
        Log::error('Gagal menghapus sub-artikel: ' . $e->getMessage());
        return redirect()->route('add-article-sub')->with([
            'status'  => 'error',
            'message' => 'Terjadi kesalahan saat menghapus sub-artikel.',
        ]);
    }
}
}
