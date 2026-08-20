<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PublicPageController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicPageController::class, 'accueil'])->name('accueil');
Route::get('/presentation', [PublicPageController::class, 'presentation'])->name('presentation');
Route::get('/comment-ca-marche', [PublicPageController::class, 'commentCaMarche'])->name('comment-ca-marche');

Route::get('/dashboard', DashboardController::class)->middleware('auth')->name('dashboard');

Route::middleware(['auth', 'etudiant'])->prefix('etudiant')->name('student.')->group(function () {
    Route::get('/tableau-de-bord', StudentDashboardController::class)->name('dashboard');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/tableau-de-bord', AdminDashboardController::class)->name('dashboard');
});

require __DIR__.'/auth.php';
