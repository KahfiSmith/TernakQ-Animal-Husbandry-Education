<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\CardArticle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ArticleController extends Controller
{
    public function index()
    {
        $cardArticles = CardArticle::whereHas('articles', function($q) {
            $q->where('status', 'Disetujui');
        })
        ->withCount(['articles' => function($q) {
            $q->where('status', 'Disetujui');
        }])
        ->latest()
        ->take(8)
        ->get();

        return view('home', compact('cardArticles'));
    }

    public function showAllCards()
    {
        $cardArticles = CardArticle::whereHas('articles', function($q) {
            $q->where('status', 'Disetujui');
        })
        ->withCount(['articles' => function($q) {
            $q->where('status', 'Disetujui');
        }])
        ->latest()
        ->paginate(12);

        return view('livewire.pages.home.all-cards', compact('cardArticles'));
    }

    public function showArticles($id)
{
    try {
        $card = CardArticle::whereHas('articles', function($query) {
                $query->where('status', 'Disetujui');
            })->findOrFail($id);
        
        $articles = $card->articles()->where('status', 'Disetujui')->latest()->get();

        return view('livewire.pages.home.articles', compact('card', 'articles'));
    } catch (\Exception $e) {
        return redirect('/')->with('error', 'Card artikel tidak ditemukan atau tidak memiliki artikel yang disetujui.');
    }
}

    public function showArticleDetail($id)
{
    $article = Article::with(['subArticles' => function ($query) {
        $query->orderBy('order_number', 'asc'); // Mengurutkan dari terkecil ke terbesar
    }])->findOrFail($id);

    return view('livewire.pages.home.article-detail', compact('article'));
}

}
