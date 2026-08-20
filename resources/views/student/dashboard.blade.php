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

    @include('student.partials.nav')

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <h2 class="h6 text-muted text-uppercase">Statut de l'inscription</h2>
                        @if ($registration)
                            <x-statut-badge :statut="$registration->statut" class="fs-6" />
                        @endif
                    </div>

                    @if ($registration === null)
                        <p class="h4">Dossier non soumis</p>
                        <p class="text-muted">Complétez votre dossier pour soumettre votre demande d'inscription.</p>
                    @else
                        <p class="text-muted mb-1">
                            Soumis le {{ $registration->date_soumission?->format('d/m/Y à H:i') ?? '—' }}
                        </p>
                        @if ($registration->commentaire_admin)
                            <div class="alert alert-warning mb-3">
                                <strong>Message de l'administration :</strong> {{ $registration->commentaire_admin }}
                            </div>
                        @endif
                    @endif

                    <label class="form-label small text-muted mb-1">Progression de l'inscription</label>
                    <div class="progress" role="progressbar" aria-label="Progression de l'inscription"
                         aria-valuenow="{{ $progression }}" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar" style="width: {{ $progression }}%">{{ $progression }} %</div>
                    </div>

                    <div class="d-flex flex-wrap gap-2 mt-4">
                        @if ($registration === null || $registration->estModifiable())
                            <a href="{{ route('student.inscription.edit') }}" class="btn btn-primary">
                                {{ $registration === null ? 'Compléter mon dossier' : 'Modifier mon dossier' }}
                            </a>
                        @endif
                        <a href="{{ route('student.inscription.show') }}" class="btn btn-outline-secondary">Voir mon dossier</a>
                        <button type="button" class="btn btn-outline-success" disabled>
                            <i class="bi bi-download me-1"></i> Télécharger le reçu
                        </button>
                    </div>
                </div>
            </div>

            @include('student.partials.historique')
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h2 class="h6 text-muted text-uppercase">Informations académiques</h2>
                    <dl class="row mb-0 small">
                        <dt class="col-5 text-muted">Filière</dt>
                        <dd class="col-7">{{ $student->filiere?->nom ?? '—' }}</dd>
                        <dt class="col-5 text-muted">Niveau</dt>
                        <dd class="col-7">{{ $student->niveau?->nom ?? '—' }}</dd>
                        <dt class="col-5 text-muted">Promotion</dt>
                        <dd class="col-7">{{ $student->promotion ?? '—' }}</dd>
                        <dt class="col-5 text-muted">Année</dt>
                        <dd class="col-7">{{ $anneeActive?->nom ?? '—' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection
