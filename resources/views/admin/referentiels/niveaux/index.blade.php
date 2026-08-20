@extends('layouts.base')

@section('titre', 'Gestion des niveaux')

@section('contenu')
    <h1 class="h3 fw-bold mb-4">Niveaux</h1>

    @include('admin.partials.nav')

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h2 class="h6 text-muted text-uppercase mb-3">Ajouter un niveau</h2>
                    <form method="POST" action="{{ route('admin.niveaux.store') }}" novalidate>
                        @csrf
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" id="nom" name="nom" class="form-control @error('nom') is-invalid @enderror"
                                   value="{{ old('nom') }}" placeholder="Licence 1" required>
                            @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="ordre" class="form-label">Ordre d'affichage</label>
                            <input type="number" id="ordre" name="ordre" min="0" max="99"
                                   class="form-control @error('ordre') is-invalid @enderror" value="{{ old('ordre', 0) }}">
                            @error('ordre')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
                                <th>Ordre</th>
                                <th>Étudiants</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($niveaux as $niveau)
                                <tr>
                                    <td>{{ $niveau->nom }}</td>
                                    <td>{{ $niveau->ordre }}</td>
                                    <td>{{ $niveau->students_count }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.niveaux.edit', $niveau) }}" class="btn btn-sm btn-outline-primary">Modifier</a>
                                        <form method="POST" action="{{ route('admin.niveaux.destroy', $niveau) }}" class="d-inline"
                                              onsubmit="return confirm('Supprimer ce niveau ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-4">Aucun niveau enregistré.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-3">{{ $niveaux->links() }}</div>
        </div>
    </div>
@endsection
