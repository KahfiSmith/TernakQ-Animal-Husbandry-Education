<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubArticle extends Model
{
    protected $fillable = [
    'article_id', 
    'title', 
    'image', 
    'content', 
    'order_number',
    'user_id',
];
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class, 'article_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
