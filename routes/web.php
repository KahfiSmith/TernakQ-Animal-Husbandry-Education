<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ChickenManagementController;

Route::get('/', [ArticleController::class, 'index'])->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    // Route Populasi Ayam
    Route::get('/populasi', [PopulasiHarianController::class, 'indexPopulasi'])->name('populasi.index');
    Route::post('/populasi', [PopulasiHarianController::class, 'storePopulasi'])->name('populasi.store');

    // Route Data Harian Ayam
    Route::get('/harian/{id}', [PopulasiHarianController::class, 'showHarian'])->name('harian.show');
    Route::post('/harian', [PopulasiHarianController::class, 'storeHarian'])->name('harian.store');
});
Route::view('chicken-management', 'chicken-management')
    ->middleware(['auth', 'verified'])
    ->name('chicken-management');
Route::view('cage-management', 'cage-management')
    ->middleware(['auth', 'verified'])
    ->name('cage-management');
Route::view('food-management', 'food-management')
    ->middleware(['auth', 'verified'])
    ->name('food-management');
Route::view('finance-management', 'finance-management')
    ->middleware(['auth', 'verified'])
    ->name('finance-management');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::get('/content', [ArticleController::class, 'showAllCards'])->name('cards');
Route::get('/content/{id}/articles', [ArticleController::class, 'showArticles'])->name('cards.articles');
Route::get('/articles/{id}', [ArticleController::class, 'showArticleDetail'])->name('articles.detail');
Route::get('sidebar', function () {
    return view('sidebar');
})->name('sidebar');

require __DIR__.'/auth.php';
 
