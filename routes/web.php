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

    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/'); 
    })->name('logout');

Route::get('/content', [ArticleController::class, 'showAllCards'])->name('cards');
Route::get('/content/{id}/articles', [ArticleController::class, 'showArticles'])->name('cards.articles');
Route::get('/articles/{id}', [ArticleController::class, 'showArticleDetail'])->name('articles.detail');
Route::get('sidebar', function () {
    return view('sidebar');
})->name('sidebar');

require __DIR__.'/auth.php';
 