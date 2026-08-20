@extends('layouts.base')

@section('titre', 'Tableau de bord étudiant')

@section('contenu')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h1 class="h3 fw-bold mb-1">Bienvenue, {{ $student->prenom }}</h1>
            <p class="text-muted mb-0">{{ $student->nom_complet }} — INE {{ $student->ine }}</p>
        </div>
        <span class="badge text-bg-secondary fs-6">
            Année académique : {{ $anneeActive?->nom ?? 'non définie' }}
        </span>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h2 class="h6 text-muted text-uppercase">Statut de l'inscription</h2>
                    @if ($registration === null)
                        <p class="h4 mb-2">Dossier non soumis</p>
                        <p class="text-muted mb-0">Complétez votre dossier pour soumettre votre demande d'inscription.</p>
                    @else
                        <p class="h4 mb-2">{{ $registration->libelle_statut }}</p>
                        <p class="text-muted mb-0">
                            Soumis le {{ $registration->date_soumission?->format('d/m/Y à H:i') ?? '—' }}
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h2 class="h6 text-muted text-uppercase">Informations académiques</h2>
                    <dl class="row mb-0 small">
                        <dt class="col-5 text-muted">Filière</dt>
                        <dd class="col-7">{{ $student->filiere?->nom ?? '—' }}</dd>
                        <dt class="col-5 text-muted">Niveau</dt>
                        <dd class="col-7">{{ $student->niveau?->nom ?? '—' }}</dd>
                        <dt class="col-5 text-muted">Promotion</dt>
                        <dd class="col-7">{{ $student->promotion ?? '—' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection
