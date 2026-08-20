<?php

namespace App\Http\Controllers;

use App\Models\Filiere;
use App\Models\Student;
use Illuminate\View\View;

class PublicPageController extends Controller
{
    public function accueil(): View
    {
        return view('public.accueil', [
            'nombreEtudiants' => Student::count(),
            'nombreFilieres' => Filiere::count(),
        ]);
    }

    public function presentation(): View
    {
        return view('public.presentation');
    }

    public function commentCaMarche(): View
    {
        return view('public.comment-ca-marche');
    }
}
