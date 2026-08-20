@extends('layouts.base')

@section('titre', 'Mon profil')

@section('contenu')
    <h1 class="h3 fw-bold mb-4">Mon profil</h1>

    @include('student.partials.nav')

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h2 class="h6 text-muted text-uppercase mb-3">Coordonnées</h2>
                    <form method="POST" action="{{ route('student.profil.contact') }}" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', auth()->user()->email) }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="telephone" class="form-label">Téléphone</label>
                            <input type="text" id="telephone" name="telephone"
                                   class="form-control @error('telephone') is-invalid @enderror"
                                   value="{{ old('telephone', auth()->user()->telephone) }}" required>
                            @error('telephone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h2 class="h6 text-muted text-uppercase mb-3">Mot de passe</h2>
                    <form method="POST" action="{{ route('student.profil.password') }}" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Mot de passe actuel</label>
                            <input type="password" id="current_password" name="current_password"
                                   class="form-control @error('current_password') is-invalid @enderror" required>
                            @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Nouveau mot de passe</label>
                            <input type="password" id="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmation</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Modifier le mot de passe</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h2 class="h6 text-muted text-uppercase mb-3">Informations universitaires</h2>
                    <dl class="row small mb-0">
                        <dt class="col-sm-3 text-muted">INE</dt><dd class="col-sm-9">{{ $student->ine }}</dd>
                        <dt class="col-sm-3 text-muted">Nom complet</dt><dd class="col-sm-9">{{ $student->nom_complet }}</dd>
                        <dt class="col-sm-3 text-muted">Filière</dt><dd class="col-sm-9">{{ $student->filiere?->nom ?? '—' }}</dd>
                        <dt class="col-sm-3 text-muted">Niveau</dt><dd class="col-sm-9">{{ $student->niveau?->nom ?? '—' }}</dd>
                        <dt class="col-sm-3 text-muted">Promotion</dt><dd class="col-sm-9">{{ $student->promotion ?? '—' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection
