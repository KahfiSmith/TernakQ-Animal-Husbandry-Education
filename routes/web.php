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
use App\Http\Controllers\UserArticleController;
use App\Http\Controllers\UserSubArticleController;
use App\Http\Controllers\AdminArticleAccController;
use App\Http\Controllers\AdminCardArticleController;
use App\Http\Controllers\AdminArticleController;
use App\Http\Controllers\AdminSubArticleController;
use App\Http\Controllers\DashboardController;
use App\Livewire\Forum\TopicList;
use App\Livewire\Forum\CreateTopic;
use App\Livewire\Forum\TopicView;

// ROUTES
Route::get('/redirect-after-login', function () {
    if (Auth::check() && Auth::user()->role === 'admin') {
        return redirect()->route('admin.article-management'); 
    }
    return redirect()->route('dashboard'); 
});

Route::middleware(['auth', 'user'])->group(function () {

    // FORUM
    Route::get('/forum', TopicList::class)->name('forum.index');
    Route::get('/forum/create', CreateTopic::class)->name('topics.create');
    Route::get('/forum/topics/{topic}', TopicView::class)->name('topics.show');

    // DASHBOARD
    Route::get('dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

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

    Route::get('/get-available-chicken-count/{batchId}/{recordId?}', [PopulasiHarianController::class, 'getAvailableChickenCount'])->name('get-available-chicken-count');
    Route::get('/get-harian-record/{id}', [PopulasiHarianController::class, 'getHarianRecord']);
    
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

    // MANAJEMEN PENAPATAN
    Route::get('/finance-management-income', [PendapatanController::class, 'indexPendapatan'])->name('finance-management-income');
    Route::post('/finance-management-income', [PendapatanController::class, 'storePendapatan'])->name('pendapatan.store');
    Route::put('/finance-management-income/{id}', [PendapatanController::class, 'updatePendapatan'])->name('pendapatan.update');
    Route::delete('/finance-management-income/{id}', [PendapatanController::class, 'destroyPendapatan'])->name('pendapatan.destroy');

    // MANAJEMEN PENGELUARAN
    Route::get('/finance-management-outcome', [PengeluaranController::class, 'indexPengeluaran'])->name('finance-management-outcome');
    Route::post('/finance-management-outcome', [PengeluaranController::class, 'storePengeluaran'])->name('pengeluaran.store');
    Route::put('/finance-management-outcome/{id}', [PengeluaranController::class, 'updatePengeluaran'])->name('pengeluaran.update');
    Route::delete('/finance-management-outcome/{id}', [PengeluaranController::class, 'destroyPengeluaran'])->name('pengeluaran.destroy');

    // TAMBAH ARTIKEL GRUP
    Route::get('/add-article', [UserCardArticleController::class, 'indexUserArtikel'])->name('add-article');
    Route::post('/add-article', [UserCardArticleController::class, 'storeUserArtikel'])->name('user-article.store');
    Route::put('/add-article/{id}', [UserCardArticleController::class, 'updateUserArtikel'])->name('user-article.update');
    Route::delete('/add-article/{id}', [UserCardArticleController::class, 'deleteUserArtikel'])->name('user-article.destroy');

    // TAMBAH ARTIKEL
    Route::get('/add-article-detail', [UserArticleController::class, 'indexUserArtikel'])->name('add-article-detail');
    Route::post('/add-article-detail', [UserArticleController::class, 'storeUserArtikel'])->name('user-article-detail.store');
    Route::put('/add-article-detail/{id}', [UserArticleController::class, 'updateUserArtikel'])->name('user-article-detail.update');
    Route::delete('/add-article-detail/{id}', [UserArticleController::class, 'deleteUserArtikel'])->name('user-article-detail.destroy');

    // TAMBAH SUB ARTIKEL
    Route::get('/add-article-sub', [UserSubArticleController::class, 'indexUserArtikel'])->name('add-article-sub');
    Route::post('/add-article-sub-multiple', [UserSubArticleController::class, 'storeMultipleSubArticles'])->name('user-article-sub.store-multiple');
    Route::put('/add-article-sub/{id}', [UserSubArticleController::class, 'updateUserArtikel'])->name('user-article-sub.update');
    Route::get('/add-article-sub/{id}/edit', [UserSubArticleController::class, 'editUserArtikel'])->name('user-article-sub.edit');
    Route::delete('/add-article-sub/{id}', [UserSubArticleController::class, 'deleteUserArtikel'])->name('user-article-sub.destroy');

});

// ADMIN ROUTES
Route::middleware(['auth', 'admin'])->group(function () {
    // MANAJEMEN ARTIKEL
    Route::get('/admin/article-management', [AdminArticleAccController::class, 'indexAdminArticle'])->name('admin.article-management');
    Route::get('/admin/article-management/{id}/edit', [AdminArticleAccController::class, 'editArticle'])->name('admin.article-management.edit');
    Route::put('/admin/article-management/{id}', [AdminArticleAccController::class, 'updateArticle'])->name('admin.article-management.update');

    // TAMBAH ARTIKEL GRUP
    Route::get('/admin/add-article', [AdminCardArticleController::class, 'indexAdminArtikel'])->name('admin.add-article');
    Route::post('/admin/add-article', [AdminCardArticleController::class, 'storeAdminArtikel'])->name('admin.user-article.store');
    Route::put('/admin/add-article/{id}', [AdminCardArticleController::class, 'updateAdminArtikel'])->name('admin.user-article.update');
    Route::delete('/admin/add-article/{id}', [AdminCardArticleController::class, 'deleteAdminArtikel'])->name('admin.user-article.destroy');

    // TAMBAH ARTIKEL
    Route::get('/admin/add-article-detail', [AdminArticleController::class, 'indexAdminArtikel'])->name('admin.add-article-detail');
    Route::post('/admin/add-article-detail', [AdminArticleController::class, 'storeAdminArtikel'])->name('admin.user-article-detail.store');
    Route::put('/admin/add-article-detail/{id}', [AdminArticleController::class, 'updateAdminArtikel'])->name('admin.user-article-detail.update');
    Route::delete('/admin/add-article-detail/{id}', [AdminArticleController::class, 'deleteAdminArtikel'])->name('admin.user-article-detail.destroy');

    // TAMBAH SUB ARTIKEL
    Route::get('/admin/add-article-sub', [AdminSubArticleController::class, 'indexAdminArtikel'])->name('admin.add-article-sub');
    Route::post('/admin/add-article-sub-multiple', [AdminSubArticleController::class, 'storeMultipleSubArticles'])->name('admin.user-article-sub.store-multiple');
    Route::put('/admin/add-article-sub/{id}', [AdminSubArticleController::class, 'updateAdminArtikel'])->name('admin.user-article-sub.update');
    Route::get('/admin/add-article-sub/{id}/edit', [AdminSubArticleController::class, 'editAdminArtikel'])->name('admin.user-article-sub.edit');
    Route::delete('/admin/add-article-sub/{id}', [AdminSubArticleController::class, 'deleteAdminArtikel'])->name('admin.user-article-sub.destroy');  
});

// PUBLIC ROUTES
Route::get('/', [ArticleController::class, 'index'])->name('home');
Route::view('profile', 'profile')->middleware(['auth'])->name('profile');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
Route::get('/content', [ArticleController::class, 'showAllCards'])->name('cards');
Route::get('/content/{id}/articles', [ArticleController::class, 'showArticles'])->name('cards.articles');
Route::get('/articles/{id}', [ArticleController::class, 'showArticleDetail'])->name('articles.detail');

require __DIR__.'/auth.php';
