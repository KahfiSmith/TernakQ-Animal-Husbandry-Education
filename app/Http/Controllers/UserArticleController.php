<?php

namespace App\Http\Controllers;

use App\Models\CardArticle;  
use App\Models\Article;      
use App\Models\Tag;          
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class UserArticleController extends Controller
{
    public function indexUserArtikel(Request $request)
    {
        try {
            $articlePage = $request->get('article_page', 1);

            $articles = Article::with('cardArticle')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(4, ['*'], 'article_page', $articlePage);

            $cardArticles = CardArticle::where('user_id', Auth::id())->get();
            $tags = Tag::all();

            $articles->appends(['article_page' => $articlePage]);

            $totalArticles = Article::where('user_id', Auth::id())->count();
            $todayArticles = Article::where('user_id', Auth::id())
                            ->whereDate('created_at', now())
                            ->count();

            return view('add-article-detail', compact(
                'articles', 
                'cardArticles', 
                'tags',
                'totalArticles', 
                'todayArticles'
            ));
        } catch (\Exception $e) {
            Log::error('Gagal memuat data artikel: ' . $e->getMessage());

            return redirect()->route('add-article-detail')->with([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memuat data artikel.',
            ]);
        }
    }

    public function storeUserArtikel(Request $request)
    {
        try {
            $validated = $request->validate([
                'card_id' => 'required|exists:card_articles,id',
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'status' => 'required|string|in:Tertunda,Disetujui,Ditolak',
                'tags' => 'required|array|min:1|max:3', 
                'tags.*' => 'exists:tags,id', 
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ], [
                'card_id.required' => 'Artikel grup harus dipilih',
                'card_id.exists' => 'Artikel grup yang dipilih tidak valid',
                'title.required' => 'Judul artikel harus diisi',
                'title.max' => 'Judul artikel maksimal 255 karakter',
                'description.required' => 'Deskripsi artikel harus diisi',
                'tags.required' => 'Minimal satu tag harus dipilih',
                'tags.array' => 'Format tag tidak valid',
                'tags.min' => 'Minimal satu tag harus dipilih',
                'tags.max' => 'Maksimal tiga tag yang dapat dipilih',
                'tags.*.exists' => 'Tag yang dipilih tidak valid',
                'image.required' => 'Gambar artikel harus diunggah',
                'image.image' => 'File harus berupa gambar',
                'image.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
                'image.max' => 'Ukuran gambar maksimal 2MB',
            ]);

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('articles', 'public');
            }

            $article = Article::create([
                'card_id' => $validated['card_id'],
                'title' => $validated['title'],
                'description' => $validated['description'],
                'status' => $validated['status'],
                'image' => $imagePath,
                'user_id' => Auth::id(),
            ]);

            if (!empty($validated['tags'])) {
                $article->tags()->attach($validated['tags']);
            }

            return redirect()->route('add-article-detail')->with([
                'status' => 'success',
                'message' => 'Artikel berhasil dibuat!',
            ]);
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan artikel: ' . $e->getMessage());

            return redirect()->back()->withInput()->with([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan artikel: ' . $e->getMessage(),
            ]);
        }
    }

    public function updateUserArtikel(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'card_id' => 'required|exists:card_articles,id',
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'status' => 'required|string|in:Tertunda,Disetujui,Ditolak',
                'tags' => 'required|array|min:1|max:3', 
                'tags.*' => 'exists:tags,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ], [
                'card_id.required' => 'Artikel grup harus dipilih',
                'card_id.exists' => 'Artikel grup yang dipilih tidak valid',
                'title.required' => 'Judul artikel harus diisi',
                'title.max' => 'Judul artikel maksimal 255 karakter',
                'description.required' => 'Deskripsi artikel harus diisi',
                'tags.required' => 'Minimal satu tag harus dipilih',
                'tags.array' => 'Format tag tidak valid',
                'tags.min' => 'Minimal satu tag harus dipilih',
                'tags.max' => 'Maksimal tiga tag yang dapat dipilih',
                'tags.*.exists' => 'Tag yang dipilih tidak valid',
                'image.image' => 'File harus berupa gambar',
                'image.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
                'image.max' => 'Ukuran gambar maksimal 2MB',
            ]);

            $article = Article::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

            $imagePath = $article->image;
            if ($request->hasFile('image')) {
                if ($article->image && Storage::disk('public')->exists($article->image)) {
                    Storage::disk('public')->delete($article->image);
                }
                $imagePath = $request->file('image')->store('articles', 'public');
            }

            $article->update([
                'card_id' => $validated['card_id'],  
                'title' => $validated['title'], 
                'description' => $validated['description'],
                'status' => $validated['status'],
                'image' => $imagePath,
            ]);

            if (!empty($validated['tags'])) {
                $article->tags()->sync($validated['tags']);
            } else {
                $article->tags()->detach(); 
            }

            return redirect()->route('add-article-detail')->with([
                'status' => 'success',
                'message' => 'Artikel berhasil diperbarui!',
            ]);
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui artikel: ' . $e->getMessage());
            return redirect()->back()->withInput()->with([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui artikel: ' . $e->getMessage(),
            ]);
        }
    }

    public function deleteUserArtikel($id)
    {
        try {
            $article = Article::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

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
