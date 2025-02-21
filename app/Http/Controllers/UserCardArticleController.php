<?php

namespace App\Http\Controllers;

use App\Models\CardArticle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserCardArticleController extends Controller
{
    public function indexUserArtikel(Request $request)
    {
        try {
            $artikelPage = $request->get('artikel_page', 1);
            $articles = CardArticle::latest()->paginate(5, ['*'], 'artikel_page', $artikelPage);
            return view('add-card-article', compact('articles'));
        } catch (\Exception $e) {
            Log::error('Gagal memuat data artikel: ' . $e->getMessage());
            return redirect()->route('add-article')->with([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memuat data artikel.',
            ]);
        }
    }

    public function storeUserArtikel(Request $request)
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

        // Simpan artikel terkait
        // $cardArticle->articles()->create([ // Menambahkan artikel terkait pada card_article
        //     'title' => $validated['title'],
        //     'description' => $validated['description'],
        //     'image' => $imagePath,
        // ]);

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

public function updateUserArtikel(Request $request, $id)
{
    try {
        $cardArticle = CardArticle::findOrFail($id); // Mengambil data CardArticle berdasarkan ID

        // Validasi data yang diinput
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Simpan gambar jika ada, jika tidak menggunakan gambar lama
        $imagePath = $cardArticle->image;
        if ($request->hasFile('image')) {
            // Pastikan direktori storage ada
            $imagePath = $request->file('image')->store('card_articles', 'public');
        }

        // Update data pada CardArticle
        $cardArticle->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'image' => $imagePath,
        ]);

        // Jika artikel terkait ada, update juga artikel terkait
        if ($cardArticle->articles->isNotEmpty()) {
            // Update artikel terkait satu per satu jika ada
            foreach ($cardArticle->articles as $article) {
                $article->update([
                    'title' => $validated['title'],
                    'description' => $validated['description'],
                    'image' => $imagePath,
                ]);
            }
        }

        // Jika tidak ada artikel terkait, maka hanya update cardArticle
        // Jika ada artikel terkait, update semuanya

        return redirect()->route('add-article')->with([
            'status' => 'success',
            'message' => 'Artikel grup berhasil diperbarui!',
        ]);
    } catch (\Exception $e) {
        // Tangkap pesan error yang lebih detail
        Log::error('Gagal memperbarui artikel: ' . $e->getMessage());

        return redirect()->route('add-article')->with([
            'status' => 'error',
            'message' => 'Terjadi kesalahan saat memperbarui artikel. Error: ' . $e->getMessage(),
        ]);
    }
}

    public function deleteUserArtikel($id)
    {
        try {
            $cardArticle = CardArticle::findOrFail($id);
            $cardArticle->delete();

            return response()->json(['success' => true, 'message' => 'Artikel berhasil dihapus.']);
        } catch (\Exception $e) {
            Log::error('Gagal menghapus artikel: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menghapus artikel.'], 500);
        }
    }
}
