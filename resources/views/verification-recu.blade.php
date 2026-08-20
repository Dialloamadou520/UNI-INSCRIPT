@extends('layouts.base')

@section('titre', 'Vérification du reçu')

@section('contenu')
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <h1 class="h3 fw-bold mb-4">Vérification d'un reçu d'inscription</h1>

            @if ($registration === null)
                <div class="alert alert-danger">
                    <i class="bi bi-x-octagon me-1"></i>
                    Aucun reçu valide ne correspond au numéro <strong>{{ $numero }}</strong>.
                </div>
            @else
                <div class="alert alert-success">
                    <i class="bi bi-patch-check me-1"></i> Reçu authentique.
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <dl class="row small mb-0">
                            <dt class="col-sm-5 text-muted">Numéro d'inscription</dt>
                            <dd class="col-sm-7 font-monospace">{{ $registration->numero_inscription }}</dd>
                            <dt class="col-sm-5 text-muted">Étudiant</dt>
                            <dd class="col-sm-7">{{ $registration->student->nom_complet }}</dd>
                            <dt class="col-sm-5 text-muted">INE</dt>
                            <dd class="col-sm-7">{{ $registration->student->ine }}</dd>
                            <dt class="col-sm-5 text-muted">Filière</dt>
                            <dd class="col-sm-7">{{ $registration->student->filiere?->nom ?? '—' }}</dd>
                            <dt class="col-sm-5 text-muted">Niveau</dt>
                            <dd class="col-sm-7">{{ $registration->student->niveau?->nom ?? '—' }}</dd>
                            <dt class="col-sm-5 text-muted">Année académique</dt>
                            <dd class="col-sm-7">{{ $registration->academicYear?->nom ?? '—' }}</dd>
                            <dt class="col-sm-5 text-muted">Validé le</dt>
                            <dd class="col-sm-7">{{ $registration->date_validation?->format('d/m/Y') ?? '—' }}</dd>
                        </dl>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
