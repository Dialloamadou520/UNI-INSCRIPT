<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TraitementRequest;
use App\Models\AcademicYear;
use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\Registration;
use App\Models\Student;
use App\Services\RegistrationService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function __construct(private readonly RegistrationService $registrations) {}

    public function index(Request $request): View
    {
        return view('admin.inscriptions.index', [
            'inscriptions' => $this->filtrer($request),
            'filieres' => Filiere::orderBy('nom')->get(),
            'niveaux' => Niveau::orderBy('nom')->get(),
            'annees' => AcademicYear::orderByDesc('nom')->get(),
            'promotions' => Student::query()
                ->whereNotNull('promotion')
                ->distinct()
                ->orderBy('promotion')
                ->pluck('promotion'),
        ]);
    }

    public function show(Registration $registration): View
    {
        $registration->load(['student.filiere', 'student.niveau', 'academicYear', 'histories.user']);

        return view('admin.inscriptions.show', ['registration' => $registration]);
    }

    public function traiter(TraitementRequest $request, Registration $registration): RedirectResponse
    {
        $donnees = $request->validated();

        $this->registrations->traiter(
            $registration,
            $donnees['statut'],
            $donnees['commentaire'] ?? null,
        );

        return redirect()->route('admin.inscriptions.show', $registration)
            ->with('status', 'Le dossier a été mis à jour : '.Registration::STATUTS[$donnees['statut']].'.');
    }

    /**
     * @return LengthAwarePaginator<int, Registration>
     */
    private function filtrer(Request $request): LengthAwarePaginator
    {
        return Registration::query()
            ->with(['student.filiere', 'student.niveau', 'academicYear'])
            ->when($request->filled('statut'), fn ($q) => $q->where('statut', $request->string('statut')))
            ->when($request->filled('academic_year_id'), fn ($q) => $q->where('academic_year_id', $request->integer('academic_year_id')))
            ->whereHas('student', function ($q) use ($request) {
                $q->when($request->filled('filiere_id'), fn ($s) => $s->where('filiere_id', $request->integer('filiere_id')))
                    ->when($request->filled('niveau_id'), fn ($s) => $s->where('niveau_id', $request->integer('niveau_id')))
                    ->when($request->filled('promotion'), fn ($s) => $s->where('promotion', $request->string('promotion')))
                    ->when($request->filled('recherche'), function ($s) use ($request) {
                        $terme = '%'.$request->string('recherche')->trim().'%';

                        $s->where(fn ($w) => $w->whereLike('ine', $terme, caseSensitive: false)
                            ->orWhereLike('nom', $terme, caseSensitive: false)
                            ->orWhereLike('prenom', $terme, caseSensitive: false));
                    });
            })
            ->latest('date_soumission')
            ->paginate(15)
            ->withQueryString();
    }
}
