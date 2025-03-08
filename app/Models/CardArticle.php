<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Tambahkan namespace ini!

class CardArticle extends Model
{
    use HasFactory;
    protected $table = 'card_articles';
    protected $fillable = [
    'title', 
    'description', 
    'image',
    'user_id',
];
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'card_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::deleting(function ($card) {
            // Hapus semua Articles terkait
            $card->articles()->each(function ($article) {
                // Hapus semua SubArticles terkait dengan Article
                $article->subArticles()->delete();
                // Hapus Article
                $article->delete();
            });
        });
    }
}
