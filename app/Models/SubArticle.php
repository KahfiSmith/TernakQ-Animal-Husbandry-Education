<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubArticle extends Model
{
    protected $fillable = ['article_id', 'title', 'image', 'content', 'order_number'];
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class, 'article_id');
    }
}
