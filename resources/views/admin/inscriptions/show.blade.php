@extends('layouts.base')

@section('titre', 'Dossier de '.$registration->student->nom_complet)

@section('contenu')
    @php($student = $registration->student)

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">{{ $student->nom_complet }}</h1>
            <p class="text-muted mb-0">INE {{ $student->ine }} — {{ $registration->academicYear?->nom }}</p>
        </div>
        <x-statut-badge :statut="$registration->statut" class="fs-6" />
    </div>

    @include('admin.partials.nav')

    <div class="row g-3">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h2 class="h6 text-muted text-uppercase mb-3">Dossier de l'étudiant</h2>
                    <dl class="row small mb-0">
                        <dt class="col-sm-4 text-muted">Numéro d'inscription</dt><dd class="col-sm-8 font-monospace">{{ $registration->numero_inscription ?? '—' }}</dd>
                        <dt class="col-sm-4 text-muted">Date de naissance</dt><dd class="col-sm-8">{{ $student->date_naissance?->format('d/m/Y') ?? '—' }}</dd>
                        <dt class="col-sm-4 text-muted">Lieu de naissance</dt><dd class="col-sm-8">{{ $student->lieu_naissance ?? '—' }}</dd>
                        <dt class="col-sm-4 text-muted">Sexe</dt><dd class="col-sm-8">{{ $student->sexe ?? '—' }}</dd>
                        <dt class="col-sm-4 text-muted">Nationalité</dt><dd class="col-sm-8">{{ $student->nationalite ?? '—' }}</dd>
                        <dt class="col-sm-4 text-muted">Adresse</dt><dd class="col-sm-8">{{ $student->adresse ?? '—' }}</dd>
                        <dt class="col-sm-4 text-muted">Téléphone</dt><dd class="col-sm-8">{{ $student->telephone ?? '—' }}</dd>
                        <dt class="col-sm-4 text-muted">Email</dt><dd class="col-sm-8">{{ $student->email ?? '—' }}</dd>
                        <dt class="col-sm-4 text-muted">Tuteur</dt><dd class="col-sm-8">{{ $student->tuteur_nom_complet ?: '—' }}</dd>
                        <dt class="col-sm-4 text-muted">Téléphone du tuteur</dt><dd class="col-sm-8">{{ $student->tuteur_telephone ?? '—' }}</dd>
                        <dt class="col-sm-4 text-muted">Filière</dt><dd class="col-sm-8">{{ $student->filiere?->nom ?? '—' }}</dd>
                        <dt class="col-sm-4 text-muted">Niveau</dt><dd class="col-sm-8">{{ $student->niveau?->nom ?? '—' }}</dd>
                        <dt class="col-sm-4 text-muted">Promotion</dt><dd class="col-sm-8">{{ $student->promotion ?? '—' }}</dd>
                        <dt class="col-sm-4 text-muted">Soumis le</dt><dd class="col-sm-8">{{ $registration->date_soumission?->format('d/m/Y à H:i') ?? '—' }}</dd>
                        <dt class="col-sm-4 text-muted">Validé le</dt><dd class="col-sm-8">{{ $registration->date_validation?->format('d/m/Y à H:i') ?? '—' }}</dd>
                    </dl>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h2 class="h6 text-muted text-uppercase mb-3">Historique</h2>
                    @if ($registration->histories->isEmpty())
                        <p class="text-muted mb-0">Aucune action enregistrée.</p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach ($registration->histories->sortByDesc('created_at') as $historique)
                                <li class="list-group-item px-0">
                                    <div class="d-flex justify-content-between gap-2">
                                        <span class="fw-semibold text-capitalize">{{ str_replace('_', ' ', $historique->action) }}</span>
                                        <span class="text-muted small">{{ $historique->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <div class="small text-muted">
                                        Par {{ $historique->user?->name ?? 'système' }}
                                        @if ($historique->nouveau_statut)
                                            — {{ \App\Models\Registration::STATUTS[$historique->nouveau_statut] ?? $historique->nouveau_statut }}
                                        @endif
                                    </div>
                                    @if ($historique->commentaire)
                                        <div class="small">{{ $historique->commentaire }}</div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h2 class="h6 text-muted text-uppercase mb-3">Traiter le dossier</h2>

                    @if ($registration->commentaire_admin)
                        <div class="alert alert-light border">
                            <strong>Dernier commentaire :</strong> {{ $registration->commentaire_admin }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.inscriptions.traiter', $registration) }}" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="statut" class="form-label">Décision</label>
                            <select id="statut" name="statut" class="form-select @error('statut') is-invalid @enderror" required>
                                @foreach ([
                                    \App\Models\Registration::STATUT_EN_COURS_VERIFICATION,
                                    \App\Models\Registration::STATUT_CORRECTION_DEMANDEE,
                                    \App\Models\Registration::STATUT_VALIDEE,
                                    \App\Models\Registration::STATUT_REJETEE,
                                ] as $statut)
                                    <option value="{{ $statut }}" @selected(old('statut') === $statut)>
                                        {{ \App\Models\Registration::STATUTS[$statut] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('statut')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="commentaire" class="form-label">
                                Commentaire <span class="text-muted small">(obligatoire pour un rejet ou une correction)</span>
                            </label>
                            <textarea id="commentaire" name="commentaire" rows="4"
                                      class="form-control @error('commentaire') is-invalid @enderror">{{ old('commentaire') }}</textarea>
                            @error('commentaire')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Enregistrer la décision</button>
                    </form>

                    <a href="{{ route('admin.inscriptions.index') }}" class="btn btn-link w-100 mt-2">Retour à la liste</a>
                </div>
            </div>
        </div>
    </div>
@endsection
