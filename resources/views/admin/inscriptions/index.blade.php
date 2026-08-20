@extends('layouts.base')

@section('titre', 'Gestion des inscriptions')

@section('contenu')
    <h1 class="h3 fw-bold mb-4">Gestion des inscriptions</h1>

    @include('admin.partials.nav')

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.inscriptions.index') }}" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label for="recherche" class="form-label small text-muted">Recherche (INE, nom, prénom)</label>
                    <input type="search" id="recherche" name="recherche" class="form-control"
                           value="{{ request('recherche') }}" placeholder="INE2025001, Diallo…">
                </div>
                <div class="col-6 col-md-2">
                    <label for="statut" class="form-label small text-muted">Statut</label>
                    <select id="statut" name="statut" class="form-select">
                        <option value="">Tous</option>
                        @foreach (\App\Models\Registration::STATUTS as $valeur => $libelle)
                            <option value="{{ $valeur }}" @selected(request('statut') === $valeur)>{{ $libelle }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label for="filiere_id" class="form-label small text-muted">Filière</label>
                    <select id="filiere_id" name="filiere_id" class="form-select">
                        <option value="">Toutes</option>
                        @foreach ($filieres as $filiere)
                            <option value="{{ $filiere->id }}" @selected((int) request('filiere_id') === $filiere->id)>{{ $filiere->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label for="niveau_id" class="form-label small text-muted">Niveau</label>
                    <select id="niveau_id" name="niveau_id" class="form-select">
                        <option value="">Tous</option>
                        @foreach ($niveaux as $niveau)
                            <option value="{{ $niveau->id }}" @selected((int) request('niveau_id') === $niveau->id)>{{ $niveau->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label for="promotion" class="form-label small text-muted">Promotion</label>
                    <select id="promotion" name="promotion" class="form-select">
                        <option value="">Toutes</option>
                        @foreach ($promotions as $promotion)
                            <option value="{{ $promotion }}" @selected(request('promotion') === $promotion)>{{ $promotion }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label for="academic_year_id" class="form-label small text-muted">Année</label>
                    <select id="academic_year_id" name="academic_year_id" class="form-select">
                        <option value="">Toutes</option>
                        @foreach ($annees as $annee)
                            <option value="{{ $annee->id }}" @selected((int) request('academic_year_id') === $annee->id)>{{ $annee->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-funnel me-1"></i> Filtrer</button>
                    <a href="{{ route('admin.inscriptions.index') }}" class="btn btn-outline-secondary">Réinitialiser</a>
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
                        <th>Soumission</th>
                        <th>Statut</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($inscriptions as $inscription)
                        <tr>
                            <td class="font-monospace">{{ $inscription->student->ine }}</td>
                            <td>{{ $inscription->student->nom }}</td>
                            <td>{{ $inscription->student->prenom }}</td>
                            <td>{{ $inscription->student->filiere?->nom ?? '—' }}</td>
                            <td>{{ $inscription->student->niveau?->nom ?? '—' }}</td>
                            <td>{{ $inscription->date_soumission?->format('d/m/Y') ?? '—' }}</td>
                            <td><x-statut-badge :statut="$inscription->statut" /></td>
                            <td class="text-end">
                                <a href="{{ route('admin.inscriptions.show', $inscription) }}" class="btn btn-sm btn-outline-primary">
                                    Voir le dossier
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Aucune inscription ne correspond à ces critères.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $inscriptions->links() }}</div>
@endsection
