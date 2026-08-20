@extends('layouts.base')

@section('titre', 'Années académiques')

@section('contenu')
    <h1 class="h3 fw-bold mb-4">Années académiques</h1>

    @include('admin.partials.nav')

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h2 class="h6 text-muted text-uppercase mb-3">Ajouter une année</h2>
                    <form method="POST" action="{{ route('admin.annees.store') }}" novalidate>
                        @csrf
                        <div class="mb-3">
                            <label for="nom" class="form-label">Année</label>
                            <input type="text" id="nom" name="nom" class="form-control @error('nom') is-invalid @enderror"
                                   value="{{ old('nom') }}" placeholder="2025-2026" required>
                            @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="form-text">Format attendu : 2025-2026.</div>
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
                                <th>Année</th>
                                <th>Statut</th>
                                <th>Inscriptions</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($annees as $annee)
                                <tr>
                                    <td>{{ $annee->nom }}</td>
                                    <td>
                                        @if ($annee->actif)
                                            <span class="badge text-bg-success">Ouverte aux inscriptions</span>
                                        @else
                                            <span class="badge text-bg-light">Fermée</span>
                                        @endif
                                    </td>
                                    <td>{{ $annee->registrations_count }}</td>
                                    <td class="text-end">
                                        @unless ($annee->actif)
                                            <form method="POST" action="{{ route('admin.annees.activer', $annee) }}" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-outline-success">Activer</button>
                                            </form>
                                        @endunless
                                        <a href="{{ route('admin.annees.edit', $annee) }}" class="btn btn-sm btn-outline-primary">Modifier</a>
                                        <form method="POST" action="{{ route('admin.annees.destroy', $annee) }}" class="d-inline"
                                              onsubmit="return confirm('Supprimer cette année académique ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-4">Aucune année enregistrée.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-3">{{ $annees->links() }}</div>
        </div>
    </div>
@endsection
