@extends('layouts.base')

@section('titre', 'Importation des étudiants')

@section('contenu')
    <h1 class="h3 fw-bold mb-4">Importation des étudiants</h1>

    @include('admin.partials.nav')

    @if (session('erreursImport') && count(session('erreursImport')) > 0)
        <div class="alert alert-warning">
            <strong>Lignes ignorées ({{ count(session('erreursImport')) }}) :</strong>
            <ul class="mb-0 mt-2">
                @foreach (session('erreursImport') as $erreur)
                    <li>{{ $erreur }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-3">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h2 class="h6 text-muted text-uppercase mb-3">Fichier CSV ou Excel</h2>
                    <form method="POST" action="{{ route('admin.etudiants.import.store') }}" enctype="multipart/form-data" novalidate>
                        @csrf
                        <div class="mb-3">
                            <label for="fichier" class="form-label">Fichier à importer</label>
                            <input type="file" id="fichier" name="fichier" accept=".csv,.txt,.xlsx,.xls"
                                   class="form-control @error('fichier') is-invalid @enderror" required>
                            @error('fichier')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="form-text">Taille maximale : 5 Mo. Les INE déjà présents sont ignorés.</div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-upload me-1"></i> Importer les étudiants
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h2 class="h6 text-muted text-uppercase mb-3">Format attendu</h2>
                    <p class="small text-muted">La première ligne du fichier doit contenir les en-têtes suivants :</p>
                    <ul class="small">
                        @foreach ($colonnes as $colonne)
                            <li><code>{{ $colonne }}</code></li>
                        @endforeach
                    </ul>
                    <p class="small text-muted mb-3">
                        Les filières et niveaux inconnus sont créés automatiquement.
                        Seuls l'INE, le nom et le prénom sont obligatoires.
                    </p>
                    <a href="{{ route('admin.etudiants.modele') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-download me-1"></i> Télécharger le modèle CSV
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
