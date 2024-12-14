<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = ['title', 'description'];

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'card_id');
    }
}
