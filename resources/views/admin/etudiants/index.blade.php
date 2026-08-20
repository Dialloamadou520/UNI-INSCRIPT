@extends('layouts.base')

@section('titre', 'Gestion des étudiants')

@section('contenu')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <h1 class="h3 fw-bold mb-0">Étudiants</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.etudiants.import') }}" class="btn btn-outline-primary">
                <i class="bi bi-upload me-1"></i> Importer
            </a>
            <a href="{{ route('admin.etudiants.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Ajouter un étudiant
            </a>
        </div>
    </div>

    @include('admin.partials.nav')

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.etudiants.index') }}" class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label for="recherche" class="form-label small text-muted">Recherche (INE, nom, prénom)</label>
                    <input type="search" id="recherche" name="recherche" class="form-control" value="{{ request('recherche') }}">
                </div>
                <div class="col-md-3">
                    <label for="filiere_id" class="form-label small text-muted">Filière</label>
                    <select id="filiere_id" name="filiere_id" class="form-select">
                        <option value="">Toutes</option>
                        @foreach ($filieres as $filiere)
                            <option value="{{ $filiere->id }}" @selected((int) request('filiere_id') === $filiere->id)>{{ $filiere->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="niveau_id" class="form-label small text-muted">Niveau</label>
                    <select id="niveau_id" name="niveau_id" class="form-select">
                        <option value="">Tous</option>
                        @foreach ($niveaux as $niveau)
                            <option value="{{ $niveau->id }}" @selected((int) request('niveau_id') === $niveau->id)>{{ $niveau->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Filtrer</button>
                    <a href="{{ route('admin.etudiants.index') }}" class="btn btn-outline-secondary">Réinitialiser</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>INE</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Filière</th>
                        <th>Niveau</th>
                        <th>Promotion</th>
                        <th>Compte</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($etudiants as $etudiant)
                        <tr>
                            <td class="font-monospace">{{ $etudiant->ine }}</td>
                            <td>{{ $etudiant->nom }}</td>
                            <td>{{ $etudiant->prenom }}</td>
                            <td>{{ $etudiant->filiere?->nom ?? '—' }}</td>
                            <td>{{ $etudiant->niveau?->nom ?? '—' }}</td>
                            <td>{{ $etudiant->promotion ?? '—' }}</td>
                            <td>
                                @if ($etudiant->user_id)
                                    <span class="badge text-bg-success">Créé</span>
                                @else
                                    <span class="badge text-bg-light">Aucun</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.etudiants.edit', $etudiant) }}" class="btn btn-sm btn-outline-primary">Modifier</a>
                                <form method="POST" action="{{ route('admin.etudiants.destroy', $etudiant) }}" class="d-inline"
                                      onsubmit="return confirm('Supprimer définitivement cet étudiant ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Aucun étudiant ne correspond à ces critères.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $etudiants->links() }}</div>
@endsection
