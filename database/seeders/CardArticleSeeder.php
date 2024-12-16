<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CardArticle;
use App\Models\Article;
use App\Models\SubArticle;

class CardArticleSeeder extends Seeder
{
    /**
     * Jalankan Seeder.
     */
    public function run(): void
    {
        $cards = [
            ['title' => 'Panduan Beternak Ayam', 'description' => 'Panduan lengkap untuk pemula'],
            ['title' => 'Pengelolaan Kesehatan Ayam', 'description' => 'Tips menjaga kesehatan ayam broiler'],
            ['title' => 'Analisis Bisnis Ayam', 'description' => 'Strategi sukses dalam bisnis peternakan ayam'],
        ];

        foreach ($cards as $cardData) {
            $card = CardArticle::create($cardData);

            for ($i = 1; $i <= 3; $i++) {
                $article = Article::create([
                    'card_id' => $card->id,
                    'title' => "Artikel {$i} untuk {$cardData['title']}",
                    'description' => "Deskripsi artikel {$i} terkait {$cardData['title']}",
                ]);

                for ($j = 1; $j <= 2; $j++) {
                    SubArticle::create([
                        'article_id' => $article->id,
                        'title' => "SubArtikel {$j} dari {$article->title}",
                        'content' => "Konten sub-artikel {$j} yang menjelaskan {$article->title}.",
                        'order_number' => $j,
                    ]);
                }
            }
        }
    }
}
