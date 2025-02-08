<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\PopulasiHarianController;
use App\Http\Controllers\KandangAyamController;

Route::get('/', [ArticleController::class, 'index'])->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/cage-management', [KandangAyamController::class, 'indexKandangManagement'])->name('cage-management');
    Route::post('/kandang', [KandangAyamController::class, 'storeKandang'])->name('kandang.store');
    Route::put('/kandang/{id}', [KandangAyamController::class, 'updateKandang'])->name('kandang.update');
    Route::delete('/kandang/{id}', [KandangAyamController::class, 'destroyKandang'])->name('kandang.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/chicken-management', [PopulasiHarianController::class, 'indexChickenManagement'])->name('chicken-management');
    Route::post('/populasi', [PopulasiHarianController::class, 'storePopulasi'])->name('populasi.store');
    Route::post('/harian', [PopulasiHarianController::class, 'storeHarian'])->name('harian.store');
    Route::delete('/populasi/{id}', [PopulasiHarianController::class, 'destroyPopulasi'])->name('populasi.destroy');
    Route::delete('/harian/{id}', [PopulasiHarianController::class, 'destroyHarian'])->name('harian.destroy');
    Route::put('/populasi/{id}', [PopulasiHarianController::class, 'updatePopulasi'])->name('populasi.update');
    Route::put('/harian/{id}', [PopulasiHarianController::class, 'updateHarian'])->name('harian.update');
    Route::get('/populasi/{id}/cetak', [PopulasiHarianController::class, 'cetak'])->name('populasi.cetak');
});

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
 
