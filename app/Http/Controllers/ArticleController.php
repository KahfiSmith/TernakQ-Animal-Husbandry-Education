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
        $card = CardArticle::findOrFail($id);
        $articles = $card->articles()->get();
        return view('livewire.pages.home.main-articles', compact('card', 'articles'));
    }

    public function showSubArticle($id)
    {
        $article = Article::findOrFail($id);
        $subArticles = $article->subBabs()->orderBy('order_number')->get();
        return view('livewire.pages.home.detail-articles', compact('article', 'subArticles'));
    }
}
