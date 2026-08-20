@extends('layouts.guest')

@section('titre', 'Création de compte')
@section('titre_carte', 'Création de votre compte')
@section('sous_titre', 'Vos informations académiques proviennent de la base officielle de l\'université.')

@section('formulaire')
    <div class="alert alert-success">
        <i class="bi bi-check-circle me-1"></i> INE vérifié avec succès.
    </div>

    <dl class="row small mb-4">
        <dt class="col-5 text-muted">Nom</dt>
        <dd class="col-7">{{ $student->nom }}</dd>
        <dt class="col-5 text-muted">Prénom</dt>
        <dd class="col-7">{{ $student->prenom }}</dd>
        <dt class="col-5 text-muted">INE</dt>
        <dd class="col-7">{{ $student->ine }}</dd>
        <dt class="col-5 text-muted">Filière</dt>
        <dd class="col-7">{{ $student->filiere?->nom ?? '—' }}</dd>
        <dt class="col-5 text-muted">Niveau</dt>
        <dd class="col-7">{{ $student->niveau?->nom ?? '—' }}</dd>
        <dt class="col-5 text-muted">Promotion</dt>
        <dd class="col-7">{{ $student->promotion ?? '—' }}</dd>
    </dl>

    <form method="POST" action="{{ route('register') }}" novalidate>
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                   name="email" value="{{ old('email', $student->email) }}" required autocomplete="email">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="telephone" class="form-label">Téléphone</label>
            <input type="text" class="form-control @error('telephone') is-invalid @enderror" id="telephone"
                   name="telephone" value="{{ old('telephone', $student->telephone) }}" required>
            @error('telephone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                   name="password" required autocomplete="new-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirmation du mot de passe</label>
            <input type="password" class="form-control" id="password_confirmation"
                   name="password_confirmation" required autocomplete="new-password">
        </div>

        <button type="submit" class="btn btn-primary w-100">Créer mon compte</button>
    </form>
@endsection
