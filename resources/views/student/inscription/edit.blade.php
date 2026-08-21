@extends('layouts.base')

@section('titre', 'Compléter mon dossier')

@section('contenu')
    <h1 class="h3 fw-bold mb-4">Dossier d'inscription</h1>

    @include('student.partials.nav')

    @if ($registration?->statut === \App\Models\Registration::STATUT_CORRECTION_DEMANDEE)
        <div class="alert alert-warning">
            <strong>Correction demandée :</strong> {{ $registration->commentaire_admin ?? 'Merci de vérifier vos informations.' }}
        </div>
    @endif

    <form method="POST" action="{{ route('student.inscription.update') }}" novalidate>
        @csrf
        @method('PUT')

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h2 class="h6 text-muted text-uppercase mb-3">Informations personnelles</h2>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nom</label>
                        <input type="text" class="form-control" value="{{ $student->nom }}" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Prénom</label>
                        <input type="text" class="form-control" value="{{ $student->prenom }}" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">INE</label>
                        <input type="text" class="form-control" value="{{ $student->ine }}" disabled>
                    </div>
                    <div class="col-md-6">
                        <label for="date_naissance" class="form-label">Date de naissance</label>
                        <input type="date" id="date_naissance" name="date_naissance"
                               class="form-control @error('date_naissance') is-invalid @enderror"
                               value="{{ old('date_naissance', $student->date_naissance?->format('Y-m-d')) }}" required>
                        @error('date_naissance')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="lieu_naissance" class="form-label">Lieu de naissance</label>
                        <input type="text" id="lieu_naissance" name="lieu_naissance"
                               class="form-control @error('lieu_naissance') is-invalid @enderror"
                               value="{{ old('lieu_naissance', $student->lieu_naissance) }}" required>
                        @error('lieu_naissance')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="sexe" class="form-label">Sexe</label>
                        <select id="sexe" name="sexe" class="form-select @error('sexe') is-invalid @enderror" required>
                            <option value="">Choisir…</option>
                            @foreach (['Masculin', 'Féminin'] as $sexe)
                                <option value="{{ $sexe }}" @selected(old('sexe', $student->sexe) === $sexe)>{{ $sexe }}</option>
                            @endforeach
                        </select>
                        @error('sexe')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="nationalite" class="form-label">Nationalité</label>
                        <input type="text" id="nationalite" name="nationalite"
                               class="form-control @error('nationalite') is-invalid @enderror"
                               value="{{ old('nationalite', $student->nationalite) }}" required>
                        @error('nationalite')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="adresse" class="form-label">Adresse</label>
                        <input type="text" id="adresse" name="adresse"
                               class="form-control @error('adresse') is-invalid @enderror"
                               value="{{ old('adresse', $student->adresse) }}" required>
                        @error('adresse')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="telephone" class="form-label">Téléphone</label>
                        <input type="text" id="telephone" name="telephone"
                               class="form-control @error('telephone') is-invalid @enderror"
                               value="{{ old('telephone', $student->telephone) }}" required>
                        @error('telephone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $student->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h2 class="h6 text-muted text-uppercase mb-3">Tuteur / personne à contacter</h2>
                <p class="text-muted small">Facultatif : utile si l'université doit joindre un proche.</p>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="tuteur_prenom" class="form-label">Prénom du tuteur</label>
                        <input type="text" id="tuteur_prenom" name="tuteur_prenom"
                               class="form-control @error('tuteur_prenom') is-invalid @enderror"
                               value="{{ old('tuteur_prenom', $student->tuteur_prenom) }}">
                        @error('tuteur_prenom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="tuteur_nom" class="form-label">Nom du tuteur</label>
                        <input type="text" id="tuteur_nom" name="tuteur_nom"
                               class="form-control @error('tuteur_nom') is-invalid @enderror"
                               value="{{ old('tuteur_nom', $student->tuteur_nom) }}">
                        @error('tuteur_nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="tuteur_telephone" class="form-label">Téléphone du tuteur</label>
                        <input type="text" id="tuteur_telephone" name="tuteur_telephone"
                               class="form-control @error('tuteur_telephone') is-invalid @enderror"
                               value="{{ old('tuteur_telephone', $student->tuteur_telephone) }}">
                        @error('tuteur_telephone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h2 class="h6 text-muted text-uppercase mb-3">Informations académiques</h2>
                <p class="text-muted small">Ces informations proviennent de la base officielle de l'université.</p>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Filière</label>
                        <input type="text" class="form-control" value="{{ $student->filiere?->nom ?? '—' }}" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Niveau</label>
                        <input type="text" class="form-control" value="{{ $student->niveau?->nom ?? '—' }}" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Promotion</label>
                        <input type="text" class="form-control" value="{{ $student->promotion ?? '—' }}" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Année académique</label>
                        <input type="text" class="form-control" value="{{ $anneeActive?->nom ?? '—' }}" disabled>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-lg">Soumettre ma demande d'inscription</button>
            <a href="{{ route('student.inscription.show') }}" class="btn btn-outline-secondary btn-lg">Annuler</a>
        </div>
    </form>
@endsection
