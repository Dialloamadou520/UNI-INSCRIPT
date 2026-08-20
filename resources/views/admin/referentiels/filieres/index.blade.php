@extends('layouts.base')

@section('titre', 'Gestion des filières')

@section('contenu')
    <h1 class="h3 fw-bold mb-4">Filières</h1>

    @include('admin.partials.nav')

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h2 class="h6 text-muted text-uppercase mb-3">Ajouter une filière</h2>
                    <form method="POST" action="{{ route('admin.filieres.store') }}" novalidate>
                        @csrf
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" id="nom" name="nom" class="form-control @error('nom') is-invalid @enderror"
                                   value="{{ old('nom') }}" required>
                            @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="code" class="form-label">Code</label>
                            <input type="text" id="code" name="code" class="form-control @error('code') is-invalid @enderror"
                                   value="{{ old('code') }}" required>
                            @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Ajouter</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nom</th>
                                <th>Code</th>
                                <th>Étudiants</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($filieres as $filiere)
                                <tr>
                                    <td>{{ $filiere->nom }}</td>
                                    <td class="font-monospace">{{ $filiere->code }}</td>
                                    <td>{{ $filiere->students_count }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.filieres.edit', $filiere) }}" class="btn btn-sm btn-outline-primary">Modifier</a>
                                        <form method="POST" action="{{ route('admin.filieres.destroy', $filiere) }}" class="d-inline"
                                              onsubmit="return confirm('Supprimer cette filière ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-4">Aucune filière enregistrée.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-3">{{ $filieres->links() }}</div>
        </div>
    </div>
@endsection
