<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminCardArticleController extends Controller
{
    // Admin melihat daftar artikel yang menunggu persetujuan
    public function pendingArticles()
    {
        $articles = Article::where('status', 'tertunda')->latest()->get();

        return response()->json([
            'message' => 'Daftar artikel yang menunggu persetujuan',
            'data' => $articles
        ]);
    }

    // Admin menyetujui artikel
    public function approve($id)
    {
        $article = Article::findOrFail($id);
        $article->update(['status' => 'disetujui']);

        return response()->json([
            'message' => 'Artikel telah disetujui!',
            'data' => $article
        ]);
    }

    // Admin menolak artikel
    public function reject($id)
    {
        $article = Article::findOrFail($id);
        $article->update(['status' => 'ditolak']);

        return response()->json([
            'message' => 'Artikel telah ditolak!',
            'data' => $article
        ]);
    }

    // Admin bisa menambahkan artikel sendiri (langsung disetujui)
    public function store(Request $request, $card_id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('articles', 'public');
        }

        $article = Article::create([
            'card_id' => $card_id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'image' => $validated['image'] ?? null,
            'status' => 'disetujui', // Admin submit, langsung disetujui
        ]);

        return response()->json([
            'message' => 'Artikel berhasil dibuat!',
            'data' => $article
        ]);
    }
}
