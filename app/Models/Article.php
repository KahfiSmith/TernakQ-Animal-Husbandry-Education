<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Article extends Model
{
    protected $fillable = ['card_id', 'title', 'catatan', 'description', 'image', 'status'];

    public function cardArticle(): BelongsTo
    {
        return $this->belongsTo(CardArticle::class, 'card_id');
    }

    public function subArticles(): HasMany
    {
        return $this->hasMany(SubArticle::class, 'article_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'article_tags');
    }
}
