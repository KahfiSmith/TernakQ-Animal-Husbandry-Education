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
            ],
            [
                'title' => 'Jenis-Jenis Ayam Petelur',
                'description' => 'Kenali berbagai jenis ayam petelur beserta produktivitas telur yang dihasilkan, serta tips dalam memilih ayam petelur berkualitas tinggi.'
            ],
            [
                'title' => 'Manajemen Pakan Ayam',
                'description' => 'Panduan lengkap mengenai jenis pakan ayam berdasarkan usia dan jenis ternak, serta cara menghitung kebutuhan pakan agar efisien dan hemat.'
            ],
            [
                'title' => 'Desain Kandang Ayam Ideal',
                'description' => 'Tips mendesain kandang ayam yang ideal, baik untuk skala kecil maupun besar, agar ayam tumbuh sehat dan produktif.'
            ],
            [
                'title' => 'Pencegahan Penyakit pada Ayam',
                'description' => 'Pelajari langkah-langkah mencegah berbagai penyakit ayam seperti flu burung, ND, dan gumboro melalui kebersihan kandang dan vaksinasi.'
            ],
            [
                'title' => 'Panduan Vaksinasi Ayam',
                'description' => 'Langkah-langkah pemberian vaksinasi ayam yang benar sesuai jadwal, serta pentingnya menjaga imunitas ternak dari berbagai penyakit.'
            ],
            [
                'title' => 'Perhitungan Biaya Ternak Ayam',
                'description' => 'Rincian lengkap mengenai estimasi biaya ternak ayam, mulai dari pembelian bibit, pakan, hingga pemeliharaan harian.'
            ],
            [
                'title' => 'Cara Menjual Hasil Ternak Ayam',
                'description' => 'Strategi efektif dalam memasarkan hasil ternak ayam, baik berupa daging, telur, atau ayam hidup, agar mendapatkan harga terbaik di pasar.'
            ],
            [
                'title' => 'Teknologi Modern dalam Peternakan Ayam',
                'description' => 'Manfaat teknologi modern seperti kandang otomatis, alat pakan otomatis, dan monitoring suhu kandang untuk meningkatkan produktivitas peternakan ayam.'
            ],
            [
                'title' => 'Tips Mengatasi Ayam Stress',
                'description' => 'Panduan mengenali tanda-tanda ayam yang mengalami stres dan langkah-langkah penanganannya agar ternak tetap sehat dan produktif.'
            ],
            [
                'title' => 'Budidaya Ayam Kampung',
                'description' => 'Panduan praktis budidaya ayam kampung, mencakup pemilihan bibit unggul, teknik pemeliharaan, dan strategi pemasaran daging ayam kampung.'
            ],
            [
                'title' => 'Keuntungan Beternak Ayam Broiler',
                'description' => 'Ulasan lengkap mengenai keuntungan beternak ayam broiler, termasuk perhitungan potensi pendapatan dari usaha ternak ayam skala kecil hingga besar.'
            ],
            [
                'title' => 'Sistem Biosekuriti di Peternakan Ayam',
                'description' => 'Pelajari pentingnya penerapan sistem biosekuriti dalam mencegah penyebaran penyakit dan menjaga kebersihan lingkungan kandang.'
            ],
        ];        

        $tags = ['Pakan Ayam', 'Manajemen Kandang', 'Kesehatan Ayam', 'Panduan', 'Tips', 'Berita'];

        foreach ($cards as $cardData) {
            // Membuat CardArticle
            $card = CardArticle::create($cardData);

            for ($i = 1; $i <= 3; $i++) {
                // Ambil tag secara acak untuk setiap artikel
                $randomTag = $tags[array_rand($tags)];

                // Membuat Artikel
                $article = Article::create([
                    'card_id' => $card->id,
                    'title' => "Artikel {$i} untuk {$cardData['title']}",
                    'description' => "Deskripsi artikel {$i} terkait {$cardData['title']}",
                    'tag' => $randomTag, // Tambahkan tagclear
                ]);

                for ($j = 1; $j <= 2; $j++) {
                    // Membuat SubArtikel
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
