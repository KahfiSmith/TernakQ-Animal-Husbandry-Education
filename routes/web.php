<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;

Route::get('/', [ArticleController::class, 'index'])->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/all-articles', [ArticleController::class, 'showAllArticle'])->name('all-articles');
Route::get('/article/{id}', [ArticleController::class, 'showArticles'])->name('articles.show');
Route::get('/sub-articles/{id}', [ArticleController::class, 'showSubArticle'])->name('sub-article.show');

require __DIR__.'/auth.php';
 