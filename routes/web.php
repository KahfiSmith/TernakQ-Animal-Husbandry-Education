<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\PopulasiHarianController;
use App\Http\Controllers\KandangAyamController;
use App\Http\Controllers\PakanController;
use App\Http\Controllers\PenggunaanPakanController;

Route::get('/', [ArticleController::class, 'index'])->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    // MANAJEMEN KANDANG
    Route::get('/cage-management', [KandangAyamController::class, 'indexKandangManagement'])->name('cage-management');
    Route::post('/cage-management', [KandangAyamController::class, 'storeKandang'])->name('kandang.store');
    Route::put('/cage-management/{id}', [KandangAyamController::class, 'updateKandang'])->name('kandang.update');
    Route::delete('/cage-management/{id}', [KandangAyamController::class, 'destroyKandang'])->name('kandang.destroy');

    // MANAJEMEN AYAM
    Route::get('/chicken-management', [PopulasiHarianController::class, 'indexChickenManagement'])->name('chicken-management');
    Route::post('/populasi', [PopulasiHarianController::class, 'storePopulasi'])->name('populasi.store');
    Route::post('/harian', [PopulasiHarianController::class, 'storeHarian'])->name('harian.store');
    Route::delete('/populasi/{id}', [PopulasiHarianController::class, 'destroyPopulasi'])->name('populasi.destroy');
    Route::delete('/harian/{id}', [PopulasiHarianController::class, 'destroyHarian'])->name('harian.destroy');
    Route::put('/populasi/{id}', [PopulasiHarianController::class, 'updatePopulasi'])->name('populasi.update');
    Route::put('/harian/{id}', [PopulasiHarianController::class, 'updateHarian'])->name('harian.update');
    Route::get('/populasi/{id}/cetak', [PopulasiHarianController::class, 'cetak'])->name('populasi.cetak');

    // MANAJEMEN PAKAN
    Route::get('/food-management', [PakanController::class, 'indexPakan'])->name('food-management');
    Route::post('/food-management', [PakanController::class, 'storePakan'])->name('pakan.store');
    Route::put('/food-management/{id}', [PakanController::class, 'updatePakan'])->name('pakan.update');
    Route::delete('/food-management/{id}', [PakanController::class, 'destroyPakan'])->name('pakan.destroy');
    Route::post('/food-usage', [PenggunaanPakanController::class, 'storePenggunaanPakan'])->name('food-usage.store');
    Route::delete('/food-usage/{id}', [PenggunaanPakanController::class, 'destroyPenggunaanPakan'])->name('food-usage.destroy');

});

Route::view('finance-management', 'finance-management')
    ->middleware(['auth', 'verified'])
    ->name('finance-management');

Route::view('/income-finance-management', 'income-finance-management')
    ->middleware(['auth', 'verified'])
    ->name('income-finance-management');

Route::view('/outcome-finance-management', 'outcome-finance-management')
    ->middleware(['auth', 'verified'])
    ->name('outcome-finance-management');

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
 
