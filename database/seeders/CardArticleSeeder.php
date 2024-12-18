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
        // Data CardArticle
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
            // Tambahkan card lainnya sesuai kebutuhan...
        ];

        // Data Tag
        $tags = ['Pakan Ayam', 'Manajemen Kandang', 'Kesehatan Ayam', 'Panduan', 'Tips', 'Berita'];

        // Simpan semua tag ke database terlebih dahulu dan ambil ID-nya
        $tagIds = [];
        foreach ($tags as $tagName) {
            $tag = Tag::firstOrCreate(['name' => $tagName]); // Hindari duplikasi
            $tagIds[] = $tag->id; // Simpan ID tag untuk digunakan nanti
        }

        // Proses pembuatan CardArticle, Artikel, dan SubArtikel
        foreach ($cards as $cardData) {
            // Buat CardArticle
            $card = CardArticle::create($cardData);

            for ($i = 1; $i <= 3; $i++) {
                // Buat Artikel terkait CardArticle
                $article = Article::create([
                    'card_id' => $card->id,
                    'title' => "Artikel {$i} untuk {$cardData['title']}",
                    'description' => "Deskripsi artikel {$i} terkait {$cardData['title']}",
                ]);

                // Hubungkan Tag secara acak ke Artikel (2 Tag)
                $randomTagIds = array_rand(array_flip($tagIds), 2); // Pilih 2 tag secara acak
                $article->tags()->attach($randomTagIds);

                for ($j = 1; $j <= 2; $j++) {
                    // Buat SubArtikel terkait Artikel
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
