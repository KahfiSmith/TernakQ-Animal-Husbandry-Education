<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\CardArticle;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;

class ArticleController extends Controller
{
    public function index()
    {
        $cardArticles = CardArticle::withCount('articles')->latest()->take(8)->get();

        return view('home', compact('cardArticles'));
    }

    public function showAllArticle()
    {
        $cardArticles = CardArticle::withCount('articles')->latest()->paginate(12);
        return view('livewire.pages.home.all-cards', compact('cardArticles'));
    }

    public function showArticles($id)
    {
        // Ambil card sesuai ID atau gagal jika tidak ditemukan
        $card = CardArticle::with('articles')->findOrFail($id);

        // Ambil semua artikel yang terkait dengan card
        $articles = $card->articles()->latest()->get();

        // Return ke view dengan data card dan articles
        return view('livewire.pages.home.articles', compact('card', 'articles'));
    }

    /**
     * Menampilkan daftar sub-articles berdasarkan ID Artikel.
     */
    public function showSubArticles($id)
    {
        // Ambil artikel sesuai ID atau gagal jika tidak ditemukan
        $article = Article::with('subArticles')->findOrFail($id);

        // Ambil semua sub-articles yang terkait dengan artikel
        $subArticles = $article->subArticles()->orderBy('order_number')->get();

        // Return ke view dengan data artikel dan sub-articles
        return view('livewire.pages.home.detail-articles', compact('article', 'subArticles'));
    }
}
