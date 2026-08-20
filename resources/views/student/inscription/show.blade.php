@extends('layouts.base')

@section('titre', 'Mon inscription')

@section('contenu')
    <h1 class="h3 fw-bold mb-4">Mon inscription</h1>

    @include('student.partials.nav')

    @if ($anneeActive === null)
        <div class="alert alert-warning">Aucune année académique n'est ouverte pour le moment.</div>
    @endif

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
                        <h2 class="h5 mb-0">Dossier {{ $anneeActive?->nom }}</h2>
                        @if ($registration)
                            <x-statut-badge :statut="$registration->statut" class="fs-6" />
                        @else
                            <span class="badge text-bg-light fs-6">Non soumis</span>
                        @endif
                    </div>

                    @if ($registration?->commentaire_admin)
                        <div class="alert alert-warning">
                            <strong>Message de l'administration :</strong> {{ $registration->commentaire_admin }}
                        </div>
                    @endif

                    <h3 class="h6 text-muted text-uppercase mt-4">Informations personnelles</h3>
                    <dl class="row small">
                        <dt class="col-sm-4 text-muted">Nom</dt><dd class="col-sm-8">{{ $student->nom }}</dd>
                        <dt class="col-sm-4 text-muted">Prénom</dt><dd class="col-sm-8">{{ $student->prenom }}</dd>
                        <dt class="col-sm-4 text-muted">INE</dt><dd class="col-sm-8">{{ $student->ine }}</dd>
                        <dt class="col-sm-4 text-muted">Date de naissance</dt><dd class="col-sm-8">{{ $student->date_naissance?->format('d/m/Y') ?? '—' }}</dd>
                        <dt class="col-sm-4 text-muted">Lieu de naissance</dt><dd class="col-sm-8">{{ $student->lieu_naissance ?? '—' }}</dd>
                        <dt class="col-sm-4 text-muted">Sexe</dt><dd class="col-sm-8">{{ $student->sexe ?? '—' }}</dd>
                        <dt class="col-sm-4 text-muted">Nationalité</dt><dd class="col-sm-8">{{ $student->nationalite ?? '—' }}</dd>
                        <dt class="col-sm-4 text-muted">Adresse</dt><dd class="col-sm-8">{{ $student->adresse ?? '—' }}</dd>
                        <dt class="col-sm-4 text-muted">Téléphone</dt><dd class="col-sm-8">{{ $student->telephone ?? '—' }}</dd>
                        <dt class="col-sm-4 text-muted">Email</dt><dd class="col-sm-8">{{ $student->email ?? '—' }}</dd>
                    </dl>

                    <h3 class="h6 text-muted text-uppercase mt-4">Informations académiques</h3>
                    <dl class="row small mb-0">
                        <dt class="col-sm-4 text-muted">Filière</dt><dd class="col-sm-8">{{ $student->filiere?->nom ?? '—' }}</dd>
                        <dt class="col-sm-4 text-muted">Niveau</dt><dd class="col-sm-8">{{ $student->niveau?->nom ?? '—' }}</dd>
                        <dt class="col-sm-4 text-muted">Promotion</dt><dd class="col-sm-8">{{ $student->promotion ?? '—' }}</dd>
                        <dt class="col-sm-4 text-muted">Année académique</dt><dd class="col-sm-8">{{ $anneeActive?->nom ?? '—' }}</dd>
                    </dl>

                    @if ($registration?->statut === \App\Models\Registration::STATUT_VALIDEE)
                        <a href="{{ route('student.inscription.recu') }}" class="btn btn-success mt-4">
                            <i class="bi bi-download me-1"></i> Télécharger mon reçu d'inscription
                        </a>
                    @endif

                    @if ($registration === null || $registration->estModifiable())
                        <a href="{{ route('student.inscription.edit') }}" class="btn btn-primary mt-4">
                            {{ $registration === null ? 'Compléter et soumettre mon dossier' : 'Modifier mon dossier' }}
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            @include('student.partials.historique')
        </div>
    </div>
@endsection
