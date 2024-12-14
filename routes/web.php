<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArtikelController;

Route::get('/', [ArtikelController::class, 'index'])->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/all-artikels', [ArtikelController::class, 'showAllArtikel']);
Route::get('/artikel/{id}', [ArtikelController::class, 'showArtikels']);
Route::get('/sub-artikels/{id}', [ArtikelController::class, 'showSubArtikels']);

require __DIR__.'/auth.php';
