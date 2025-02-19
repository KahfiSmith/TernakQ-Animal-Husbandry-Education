<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\PopulasiHarianController;
use App\Http\Controllers\KandangAyamController;
use App\Http\Controllers\PakanController;
use App\Http\Controllers\PenggunaanPakanController;
use App\Http\Controllers\PendapatanController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\UserCardArticleController;

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

    // MANAJEMEN KEUANGAN
    Route::get('/finance-management', [KeuanganController::class, 'indexKeuangan'])->name('finance-management');
    Route::get('/finance-management/pdf', [KeuanganController::class, 'exportPDF'])->name('finance-management.pdf');

    Route::get('/finance-management-income', [PendapatanController::class, 'indexPendapatan'])->name('finance-management-income');
    Route::post('/finance-management-income', [PendapatanController::class, 'storePendapatan'])->name('pendapatan.store');
    Route::put('/finance-management-income/{id}', [PendapatanController::class, 'updatePendapatan'])->name('pendapatan.update');
    Route::delete('/finance-management-income/{id}', [PendapatanController::class, 'destroyPendapatan'])->name('pendapatan.destroy');

    Route::get('/finance-management-outcome', [PengeluaranController::class, 'indexPengeluaran'])->name('finance-management-outcome');
    Route::post('/finance-management-outcome', [PengeluaranController::class, 'storePengeluaran'])->name('pengeluaran.store');
    Route::put('/finance-management-outcome/{id}', [PengeluaranController::class, 'updatePengeluaran'])->name('pengeluaran.update');
    Route::delete('/finance-management-outcome/{id}', [PengeluaranController::class, 'destroyPengeluaran'])->name('pengeluaran.destroy');

    Route::get('/add-article', [UserCardArticleController::class, 'indexUserArtikel'])->name('add-article');
    Route::post('/add-article', [UserCardArticleController::class, 'storeUserArtikel'])->name('user-article.store');
    Route::put('/add-article/{id}', [UserCardArticleController::class, 'updateUserArtikel'])->name('user-article.update');
    Route::delete('/add-article/{id}', [UserCardArticleController::class, 'deleteUserArtikel'])->name('user-article.destroy');
});

// Route::middleware(['auth', 'verified', 'admin'])->group(function () {
//     Route::get('/admin/articles', [AdminArticleController::class, 'index'])->name('admin.articles.index');
//     Route::get('/admin/articles/{id}/edit', [AdminArticleController::class, 'edit'])->name('admin.articles.edit');
//     Route::put('/admin/articles/{id}', [AdminArticleController::class, 'update'])->name('admin.articles.update');
//     Route::delete('/admin/articles/{id}', [AdminArticleController::class, 'destroy'])->name('admin.articles.destroy');
// });

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