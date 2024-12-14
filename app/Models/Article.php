<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['card_id', 'title', 'description'];

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class, 'card_id');
    }

    public function subBabs(): HasMany
    {
        return $this->hasMany(SubBab::class, 'article_id');
    }
}
