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

    public function showAllCards()
    {
        $cardArticles = CardArticle::withCount('articles')->latest()->paginate(12);
        return view('livewire.pages.home.all-cards', compact('cardArticles'));
    }

    public function showArticles($id)
    {
        $card = CardArticle::with('articles')->findOrFail($id);
        $articles = $card->articles()->latest()->get();

        return view('livewire.pages.home.articles', compact('card', 'articles'));
    }

    public function showArticleDetail($id)
    {
        $article = Article::with('subArticles')->findOrFail($id);
        $subArticles = $article->subArticles()->orderBy('order_number')->get();

        return view('livewire.pages.home.article-detail', compact('article', 'subArticles'));
    }
}
