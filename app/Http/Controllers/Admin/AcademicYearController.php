<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AcademicYearRequest;
use App\Models\AcademicYear;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AcademicYearController extends Controller
{
    public function index(): View
    {
        return view('admin.referentiels.annees.index', [
            'annees' => AcademicYear::withCount('registrations')->orderByDesc('nom')->paginate(20),
        ]);
    }

    public function store(AcademicYearRequest $request): RedirectResponse
    {
        AcademicYear::create($request->validated());

        return redirect()->route('admin.annees.index')->with('status', "L'année académique a été ajoutée.");
    }

    public function edit(AcademicYear $annee): View
    {
        return view('admin.referentiels.annees.edit', ['annee' => $annee]);
    }

    public function update(AcademicYearRequest $request, AcademicYear $annee): RedirectResponse
    {
        $annee->update($request->validated());

        return redirect()->route('admin.annees.index')->with('status', "L'année académique a été modifiée.");
    }

    /**
     * Une seule année peut être ouverte aux inscriptions à la fois.
     */
    public function activer(AcademicYear $annee): RedirectResponse
    {
        DB::transaction(function () use ($annee) {
            AcademicYear::where('id', '!=', $annee->id)->update(['actif' => false]);
            $annee->update(['actif' => true]);
        });

        return redirect()->route('admin.annees.index')
            ->with('status', "L'année {$annee->nom} est désormais ouverte aux inscriptions.");
    }

    public function destroy(AcademicYear $annee): RedirectResponse
    {
        if ($annee->registrations()->exists()) {
            return redirect()->route('admin.annees.index')
                ->with('erreur', 'Cette année porte des inscriptions et ne peut pas être supprimée.');
        }

        $annee->delete();

        return redirect()->route('admin.annees.index')->with('status', "L'année académique a été supprimée.");
    }
}
