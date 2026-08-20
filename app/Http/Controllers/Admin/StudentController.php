<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ImportEtudiantsRequest;
use App\Http\Requests\Admin\StudentRequest;
use App\Imports\StudentsImport;
use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $etudiants = Student::query()
            ->with(['filiere', 'niveau', 'user'])
            ->when($request->filled('filiere_id'), fn ($q) => $q->where('filiere_id', $request->integer('filiere_id')))
            ->when($request->filled('niveau_id'), fn ($q) => $q->where('niveau_id', $request->integer('niveau_id')))
            ->when($request->filled('recherche'), function ($q) use ($request) {
                $terme = '%'.$request->string('recherche')->trim().'%';

                $q->where(fn ($w) => $w->whereLike('ine', $terme, caseSensitive: false)
                    ->orWhereLike('nom', $terme, caseSensitive: false)
                    ->orWhereLike('prenom', $terme, caseSensitive: false));
            })
            ->orderBy('nom')
            ->paginate(15)
            ->withQueryString();

        return view('admin.etudiants.index', [
            'etudiants' => $etudiants,
            'filieres' => Filiere::orderBy('nom')->get(),
            'niveaux' => Niveau::orderBy('nom')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.etudiants.form', [
            'student' => new Student,
            'filieres' => Filiere::orderBy('nom')->get(),
            'niveaux' => Niveau::orderBy('nom')->get(),
        ]);
    }

    public function store(StudentRequest $request): RedirectResponse
    {
        Student::create($request->validated());

        return redirect()->route('admin.etudiants.index')->with('status', "L'étudiant a été ajouté.");
    }

    public function edit(Student $student): View
    {
        return view('admin.etudiants.form', [
            'student' => $student,
            'filieres' => Filiere::orderBy('nom')->get(),
            'niveaux' => Niveau::orderBy('nom')->get(),
        ]);
    }

    public function update(StudentRequest $request, Student $student): RedirectResponse
    {
        $student->update($request->validated());

        return redirect()->route('admin.etudiants.index')->with('status', "L'étudiant a été modifié.");
    }

    public function destroy(Student $student): RedirectResponse
    {
        if ($student->user_id !== null) {
            return redirect()->route('admin.etudiants.index')
                ->with('erreur', "Cet étudiant possède un compte : supprimez d'abord son compte utilisateur.");
        }

        $student->delete();

        return redirect()->route('admin.etudiants.index')->with('status', "L'étudiant a été supprimé.");
    }

    public function importForm(): View
    {
        return view('admin.etudiants.import', ['colonnes' => StudentsImport::COLONNES]);
    }

    public function import(ImportEtudiantsRequest $request): RedirectResponse
    {
        $import = new StudentsImport;

        Excel::import($import, $request->file('fichier'));

        $resultat = $import->resultat;

        return redirect()->route('admin.etudiants.import')
            ->with('status', "{$resultat->importes} étudiant(s) importé(s), {$resultat->doublons} doublon(s) ignoré(s).")
            ->with('erreursImport', $resultat->erreurs);
    }

    /**
     * Modèle CSV téléchargeable, avec une ligne d'exemple.
     */
    public function modele(): StreamedResponse
    {
        $lignes = [
            StudentsImport::COLONNES,
            ['INE2025001', 'Diallo', 'Amadou', 'amadou.diallo@universite.sn', '770000000', 'Informatique', 'Licence 1', '2025'],
        ];

        return response()->streamDownload(function () use ($lignes) {
            $sortie = fopen('php://output', 'w');

            foreach ($lignes as $ligne) {
                fputcsv($sortie, $ligne);
            }

            fclose($sortie);
        }, 'modele-import-etudiants.csv', ['Content-Type' => 'text/csv']);
    }
}
