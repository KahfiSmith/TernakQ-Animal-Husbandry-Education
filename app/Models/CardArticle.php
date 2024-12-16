<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardArticle extends Model
{
    protected $fillable = ['title', 'description', 'image'];

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'card_id');
    }
}
