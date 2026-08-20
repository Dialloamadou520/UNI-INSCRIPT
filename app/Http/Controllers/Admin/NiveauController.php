<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NiveauRequest;
use App\Models\Niveau;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NiveauController extends Controller
{
    public function index(): View
    {
        return view('admin.referentiels.niveaux.index', [
            'niveaux' => Niveau::withCount('students')->orderBy('ordre')->orderBy('nom')->paginate(20),
        ]);
    }

    public function store(NiveauRequest $request): RedirectResponse
    {
        Niveau::create($request->validated());

        return redirect()->route('admin.niveaux.index')->with('status', 'Le niveau a été ajouté.');
    }

    public function edit(Niveau $niveau): View
    {
        return view('admin.referentiels.niveaux.edit', ['niveau' => $niveau]);
    }

    public function update(NiveauRequest $request, Niveau $niveau): RedirectResponse
    {
        $niveau->update($request->validated());

        return redirect()->route('admin.niveaux.index')->with('status', 'Le niveau a été modifié.');
    }

    public function destroy(Niveau $niveau): RedirectResponse
    {
        if ($niveau->students()->exists()) {
            return redirect()->route('admin.niveaux.index')
                ->with('erreur', 'Ce niveau est rattaché à des étudiants et ne peut pas être supprimé.');
        }

        $niveau->delete();

        return redirect()->route('admin.niveaux.index')->with('status', 'Le niveau a été supprimé.');
    }
}
