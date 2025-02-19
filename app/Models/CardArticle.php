<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Tambahkan namespace ini!

class CardArticle extends Model
{
    use HasFactory;
    protected $table = 'card_articles';
    protected $fillable = ['title', 'description', 'image'];
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'card_id');
    }
}
