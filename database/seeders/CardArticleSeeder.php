<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CardArticle;
use App\Models\Article;
use App\Models\SubArticle;
use App\Models\Tag;

class CardArticleSeeder extends Seeder
{
    public function run(): void
    {
        $cards = [
            [
                'title' => 'Panduan Beternak Ayam',
                'description' => 'Panduan lengkap untuk pemula dalam beternak ayam, mencakup pemilihan bibit unggul, jenis pakan berkualitas, serta perawatan kandang agar hasil ternak optimal.'
            ],
            [
                'title' => 'Pengelolaan Kesehatan Ayam',
                'description' => 'Tips penting untuk menjaga kesehatan ayam broiler, termasuk pencegahan penyakit, vaksinasi rutin, kebersihan kandang, dan pemberian pakan bernutrisi.'
            ],
            [
                'title' => 'Analisis Bisnis Ayam',
                'description' => 'Strategi sukses dalam bisnis peternakan ayam, mulai dari perencanaan modal, manajemen operasional, hingga pemasaran hasil ternak untuk meraih keuntungan maksimal.'
            ]
        ];

        $tags = ['Pakan Ayam', 'Manajemen Kandang', 'Kesehatan Ayam', 'Panduan', 'Tips', 'Berita', 'Ayam Broiler','Budidaya Ayam', 'Teknologi Peternakan','Pencegahan Penyakit','Teknik Pemeliharaan', 'Kualitas Daging Ayam', 'Peralatan Peternakan', 'Efisiensi Operasional',];

        $tagIds = [];
        foreach ($tags as $tagName) {
            $tag = Tag::firstOrCreate(['name' => $tagName]); 
            $tagIds[] = $tag->id; 
        }

        foreach ($cards as $cardData) {
            $card = CardArticle::create($cardData);

            for ($i = 1; $i <= 3; $i++) {
                $article = Article::create([
                    'card_id' => $card->id,
                    'title' => "Artikel {$i} untuk {$cardData['title']}",
                    'description' => "Deskripsi artikel {$i} terkait {$cardData['title']}",
                ]);

                $randomTagIds = array_rand(array_flip($tagIds), rand(1, 2));
                $article->tags()->attach($randomTagIds);

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
