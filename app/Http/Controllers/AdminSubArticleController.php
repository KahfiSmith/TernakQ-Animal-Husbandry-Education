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
            $subArticle = null;  
            $articles = Article::where('user_id', Auth::id())->get();
            $selectedArticle = null;  
            $subArticles = collect(); 

            $articleId = $request->query('article_id');
            if ($articleId) {
                $selectedArticle = Article::with(['subArticles' => function ($query) {
                    $query->orderBy('order_number', 'asc')
                        ->orderBy('id', 'desc');  
                }])->where('user_id', Auth::id())->find($articleId);

                if ($selectedArticle) {
                    $subArticles = $selectedArticle->subArticles;
                }
            }

            $editId = $request->query('edit_id'); 
            if ($editId) {
                $subArticle = SubArticle::where('id', $editId)
                    ->whereHas('article', function ($query) {
                        $query->where('user_id', Auth::id());
                    })
                    ->first();
            }

            return view('admin.add-article-sub', compact('subArticles', 'articles', 'selectedArticle', 'subArticle'));
        } catch (\Exception $e) {
            Log::error('Gagal memuat sub-artikel: ' . $e->getMessage());

            return redirect()->route('admin.add-article-sub')->with([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan saat memuat data sub-artikel.',
            ]);
        }
    }

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
                'sub_articles.*.remove_image' => 'nullable|in:0,1',
            ], [
                'article_id.required'           => 'Artikel induk harus dipilih.',
                'article_id.exists'             => 'Artikel induk tidak ditemukan.',
                'sub_articles.required'         => 'Minimal satu sub artikel harus diisi.',
                'sub_articles.min'              => 'Minimal satu sub artikel harus diisi.',
                'sub_articles.*.title.required' => 'Judul sub artikel harus diisi.',
                'sub_articles.*.title.max'      => 'Judul sub artikel maksimal 255 karakter.',
                'sub_articles.*.content.required' => 'Konten sub artikel harus diisi.',
                'sub_articles.*.order_number.required' => 'Urutan sub artikel harus diisi.',
                'sub_articles.*.order_number.integer'  => 'Urutan sub artikel harus berupa angka.',
                'sub_articles.*.order_number.min'      => 'Urutan sub artikel minimal 1.',
                'sub_articles.*.image.image'    => 'File harus berupa gambar.',
                'sub_articles.*.image.mimes'    => 'Format gambar harus jpeg, png, jpg, atau gif.',
                'sub_articles.*.image.max'      => 'Ukuran gambar maksimal 2MB.',
            ]);

            $article = Article::where('id', $validated['article_id'])
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

            $subArticlesData = [];
            foreach ($validated['sub_articles'] as $subArticle) {
                $imagePath = null;
                
                if (isset($subArticle['image']) && $subArticle['image'] instanceof \Illuminate\Http\UploadedFile) {
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

            return redirect()->route('admin.add-article-sub', ['article_id' => $validated['article_id']])->with([
                'status'  => 'success',
                'message' => 'Semua sub-artikel berhasil disimpan!',
            ]);
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan sub-artikel: ' . $e->getMessage());
            return redirect()->route('admin.add-article-sub')->with([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan sub-artikel: ' . $e->getMessage(),
            ]);
        }
    }

    public function updateAdminArtikel(Request $request, $id)
    {
        try {
            $subArticle = SubArticle::where('id', $id)
                ->whereHas('article', function ($query) {
                    $query->where('user_id', Auth::id());
                })
                ->firstOrFail();

            $validated = $request->validate([
                'article_id'                => 'required|exists:articles,id',
                'sub_articles'              => 'required|array',
                'sub_articles.*.title'      => 'required|string|max:255',
                'sub_articles.*.content'    => 'required|string',
                'sub_articles.*.order_number' => 'required|integer|min:1',
                'sub_articles.*.image'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'sub_articles.*.remove_image' => 'nullable|in:0,1',
            ], [
                'article_id.required'         => 'Artikel induk harus dipilih.',
                'article_id.exists'           => 'Artikel induk tidak ditemukan.',
                'sub_articles.required'       => 'Data sub artikel harus diisi.',
                'sub_articles.*.title.required' => 'Judul sub artikel harus diisi.',
                'sub_articles.*.title.max'    => 'Judul sub artikel maksimal 255 karakter.',
                'sub_articles.*.content.required' => 'Konten sub artikel harus diisi.',
                'sub_articles.*.order_number.required' => 'Urutan sub artikel harus diisi.',
                'sub_articles.*.order_number.integer'  => 'Urutan sub artikel harus berupa angka.',
                'sub_articles.*.order_number.min'      => 'Urutan sub artikel minimal 1.',
                'sub_articles.*.image.image'  => 'File harus berupa gambar.',
                'sub_articles.*.image.mimes'  => 'Format gambar harus jpeg, png, jpg, atau gif.',
                'sub_articles.*.image.max'    => 'Ukuran gambar maksimal 2MB.',
            ]);

            $removeImage = $request->input('sub_articles.0.remove_image', '0');
            $imagePath = $subArticle->image; 

            if ($removeImage === '1') {
                if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
                $imagePath = null;
            } 
            elseif ($request->hasFile('sub_articles.0.image')) {
                if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
                $imagePath = $request->file('sub_articles.0.image')->store('sub_articles', 'public');
            }
         
            $subArticle->update([
                'article_id'   => $validated['article_id'],
                'title'        => $validated['sub_articles'][0]['title'],
                'content'      => $validated['sub_articles'][0]['content'],
                'order_number' => $validated['sub_articles'][0]['order_number'],
                'image'        => $imagePath,
            ]);

            return redirect()->route('admin.add-article-sub', ['article_id' => $validated['article_id']])->with([
                'status'  => 'success',
                'message' => 'Sub-artikel berhasil diperbarui!',
            ]);
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui sub-artikel: ' . $e->getMessage());
            return redirect()->route('admin.add-article-sub')->with([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui sub-artikel: ' . $e->getMessage(),
            ]);
        }
    }

    public function editAdminArtikel($id)
    {
        try {
            $subArticle = SubArticle::where('id', $id)
                ->whereHas('article', function ($query) {
                    $query->where('user_id', Auth::id());
                })
                ->firstOrFail();

            $selectedArticle = $subArticle->article;

            $articles = Article::where('user_id', Auth::id())->get();

            return view('admin.add-article-sub', compact('subArticle', 'selectedArticle', 'articles'));
        } catch (\Exception $e) {
            return redirect()->route('admin.add-article-sub')->with([
                'status' => 'error',
                'message' => 'Sub-artikel tidak ditemukan atau tidak dapat diakses.',
            ]);
        }
    }

    public function deleteAdminArtikel($id)
    {
        try {
            $subArticle = SubArticle::where('id', $id)
                ->whereHas('article', function ($query) {
                    $query->where('user_id', Auth::id());
                })
                ->firstOrFail();

            $articleId = $subArticle->article_id; 

            if ($subArticle->image) {
                Storage::disk('public')->delete($subArticle->image);
            }

            $subArticle->delete();

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Sub-artikel berhasil dihapus!'
                ]);
            }
            
            return redirect()->route('admin.add-article-sub', ['article_id' => $articleId])->with([
                'status'  => 'success',
                'message' => 'Sub-artikel berhasil dihapus!',
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal menghapus sub-artikel: ' . $e->getMessage());
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menghapus sub-artikel: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('admin.add-article-sub')->with([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan saat menghapus sub-artikel.',
            ]);
        }
    }
}
