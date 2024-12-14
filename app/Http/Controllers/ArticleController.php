<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Http\Requests\StoreArtikelRequest;
use App\Http\Requests\UpdateArtikelRequest;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::latest()->take(8)->get();

        return view('home', compact('articles'));
    }

    public function showAllArticle()
    {
        $articles = Article::latest()->paginate(10);
        return view('livewire.pages.home.all-articles', compact('articles'));
    }

    public function showArticles($id)
    {
        $card = Card::findOrFail($id);
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
