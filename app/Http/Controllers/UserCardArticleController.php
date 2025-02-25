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
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $card = CardArticle::findOrFail($id);
        $card->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
        ]);

        // Tidak mengubah judul artikel kecuali diinginkan, update artikel secara terpisah jika perlu

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
