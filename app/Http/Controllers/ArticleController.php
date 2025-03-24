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
        ->with(['articles' => function($q) {
            $q->where('status', 'Disetujui')->with('subArticles');
        }])
        ->withCount(['articles' => function($q) {
            $q->where('status', 'Disetujui');
        }])
        ->latest()
        ->take(8)
        ->get();

    $readingSpeed = 200; // kata per menit

    // Hitung durasi baca per card dengan menjumlahkan durasi baca tiap artikel
    foreach ($cardArticles as $card) {
        $totalReadingTime = 0;
        foreach ($card->articles as $article) {
            // Gabungkan judul dan deskripsi artikel
            $text = $article->title . ' ' . $article->description;
            
            // Jika ada sub artikel, tambahkan juga judul dan kontennya
            if ($article->subArticles && $article->subArticles->isNotEmpty()) {
                $subText = $article->subArticles
                    ->map(function ($sub) {
                        return $sub->title . ' ' . $sub->content;
                    })
                    ->implode(' ');
                $text .= ' ' . $subText;
            }
            
            // Hilangkan tag HTML (jika ada) dan hitung jumlah kata
            $wordCount = str_word_count(strip_tags($text));
            
            // Hitung waktu baca artikel dalam menit (menggunakan kecepatan membaca, misalnya 250 kata per menit)
            $articleReadingTime = ceil($wordCount / $readingSpeed);
            
            // Akumulasi waktu baca
            $totalReadingTime += $articleReadingTime;
        }
        // Tambahkan properti readingTime ke setiap card
        $card->readingTime = $totalReadingTime;
    }    

    return view('home', compact('cardArticles'));
}

public function showAllCards(Request $request)
{
    // Ambil kata kunci pencarian dari input
    $search = $request->get('search');
    
    // Filter card articles berdasarkan judul jika ada kata kunci pencarian
    $cardArticlesQuery = CardArticle::whereHas('articles', function ($q) {
        $q->where('status', 'Disetujui');
    })
    ->withCount(['articles' => function ($q) {
        $q->where('status', 'Disetujui');
    }])
    ->latest();

    // Jika ada pencarian, filter berdasarkan judul card
    if ($search) {
        $cardArticlesQuery->where('title', 'like', '%' . $search . '%');
    }

    // Ambil card dengan artikel yang disetujui dan hitung durasi baca
    $cardArticles = $cardArticlesQuery->paginate(8);

    $readingSpeed = 200; // kata per menit

    // Hitung durasi baca per card dengan menjumlahkan durasi baca tiap artikel
    foreach ($cardArticles as $card) {
        $totalReadingTime = 0;
        foreach ($card->articles as $article) {
            // Gabungkan judul dan deskripsi artikel
            $text = $article->title . ' ' . $article->description;

            // Jika ada sub artikel, tambahkan juga judul dan kontennya
            if ($article->subArticles && $article->subArticles->isNotEmpty()) {
                $subText = $article->subArticles
                    ->map(function ($sub) {
                        return $sub->title . ' ' . $sub->content;
                    })
                    ->implode(' ');

                $text .= ' ' . $subText;
            }

            // Hilangkan tag HTML (jika ada) dan hitung jumlah kata
            $wordCount = str_word_count(strip_tags($text));

            // Hitung waktu baca artikel dalam menit (menggunakan kecepatan membaca, misalnya 250 kata per menit)
            $articleReadingTime = ceil($wordCount / $readingSpeed);

            // Akumulasi waktu baca
            $totalReadingTime += $articleReadingTime;
        }

        // Tambahkan properti readingTime ke setiap card
        $card->readingTime = $totalReadingTime;
    }

    // Kembalikan view dengan data yang telah difilter
    return view('livewire.pages.home.all-cards', compact('cardArticles'));
}

public function showArticles(Request $request, $id)
{
    try {
        // Ambil card dengan artikel yang disetujui
        $card = CardArticle::whereHas('articles', function($query) {
                $query->where('status', 'Disetujui');
            })->findOrFail($id);

        // Ambil artikel yang disetujui beserta subArticles-nya
        $articlesQuery = $card->articles()
            ->with('subArticles')
            ->where('status', 'Disetujui');

        // Jika ada pencarian, filter artikel berdasarkan judul
        if ($request->has('search')) {
            $search = $request->get('search');
            $articlesQuery->where('title', 'like', '%' . $search . '%');
        }

        // Ambil artikel yang sudah difilter
        $articles = $articlesQuery->latest()->paginate(8);

        $readingSpeed = 200; // kata per menit

        // Hitung durasi baca untuk setiap artikel
        foreach ($articles as $article) {
            // Gabungkan judul dan deskripsi artikel
            $text = $article->title . ' ' . $article->description;
            
            // Jika artikel memiliki sub artikel, gabungkan juga judul dan kontennya
            if ($article->subArticles && $article->subArticles->isNotEmpty()) {
                $subText = $article->subArticles->map(function ($sub) {
                    return $sub->title . ' ' . $sub->content;
                })->implode(' ');

                $text .= ' ' . $subText;
            }
            
            // Hilangkan tag HTML (jika ada) dan hitung jumlah kata
            $wordCount = str_word_count(strip_tags($text));
            
            // Hitung waktu baca artikel (dalam menit, dibulatkan ke atas)
            $article->readingTime = ceil($wordCount / $readingSpeed);
        }

        // Hitung total durasi baca dari semua artikel di dalam card
        $totalReadingTime = $articles->sum('readingTime');
        $card->readingTime = $totalReadingTime;

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
