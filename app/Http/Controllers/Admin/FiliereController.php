<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FiliereRequest;
use App\Models\Filiere;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FiliereController extends Controller
{
    public function index(): View
    {
        return view('admin.referentiels.filieres.index', [
            'filieres' => Filiere::withCount('students')->orderBy('nom')->paginate(20),
        ]);
    }

    public function store(FiliereRequest $request): RedirectResponse
    {
        Filiere::create($request->validated());

        return redirect()->route('admin.filieres.index')->with('status', 'La filière a été ajoutée.');
    }

    public function edit(Filiere $filiere): View
    {
        return view('admin.referentiels.filieres.edit', ['filiere' => $filiere]);
    }

    public function update(FiliereRequest $request, Filiere $filiere): RedirectResponse
    {
        $filiere->update($request->validated());

        return redirect()->route('admin.filieres.index')->with('status', 'La filière a été modifiée.');
    }

    public function destroy(Filiere $filiere): RedirectResponse
    {
        if ($filiere->students()->exists()) {
            return redirect()->route('admin.filieres.index')
                ->with('erreur', 'Cette filière est rattachée à des étudiants et ne peut pas être supprimée.');
        }

        $filiere->delete();

        return redirect()->route('admin.filieres.index')->with('status', 'La filière a été supprimée.');
    }
}
