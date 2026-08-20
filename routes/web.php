<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\RegistrationController as AdminRegistrationController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PublicPageController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\ProfileController as StudentProfileController;
use App\Http\Controllers\Student\RegistrationController as StudentRegistrationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicPageController::class, 'accueil'])->name('accueil');
Route::get('/presentation', [PublicPageController::class, 'presentation'])->name('presentation');
Route::get('/comment-ca-marche', [PublicPageController::class, 'commentCaMarche'])->name('comment-ca-marche');

Route::get('/dashboard', DashboardController::class)->middleware('auth')->name('dashboard');

Route::middleware(['auth', 'etudiant'])->prefix('etudiant')->name('student.')->group(function () {
    Route::get('/tableau-de-bord', StudentDashboardController::class)->name('dashboard');

    Route::get('/inscription', [StudentRegistrationController::class, 'show'])->name('inscription.show');
    Route::get('/inscription/dossier', [StudentRegistrationController::class, 'edit'])->name('inscription.edit');
    Route::put('/inscription/dossier', [StudentRegistrationController::class, 'update'])->name('inscription.update');

    Route::get('/profil', [StudentProfileController::class, 'edit'])->name('profil');
    Route::put('/profil/contact', [StudentProfileController::class, 'updateContact'])->name('profil.contact');
    Route::put('/profil/mot-de-passe', [StudentProfileController::class, 'updatePassword'])->name('profil.password');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/tableau-de-bord', AdminDashboardController::class)->name('dashboard');

    Route::get('/inscriptions', [AdminRegistrationController::class, 'index'])->name('inscriptions.index');
    Route::get('/inscriptions/{registration}', [AdminRegistrationController::class, 'show'])->name('inscriptions.show');
    Route::put('/inscriptions/{registration}', [AdminRegistrationController::class, 'traiter'])->name('inscriptions.traiter');

    Route::get('/etudiants/importation', [AdminStudentController::class, 'importForm'])->name('etudiants.import');
    Route::post('/etudiants/importation', [AdminStudentController::class, 'import'])->name('etudiants.import.store');
    Route::get('/etudiants/modele', [AdminStudentController::class, 'modele'])->name('etudiants.modele');
    Route::resource('etudiants', AdminStudentController::class)
        ->parameters(['etudiants' => 'student'])
        ->except(['show']);
});

require __DIR__.'/auth.php';
