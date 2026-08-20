@extends('layouts.base')

@section('titre', $student->exists ? 'Modifier un étudiant' : 'Ajouter un étudiant')

@section('contenu')
    <h1 class="h3 fw-bold mb-4">{{ $student->exists ? 'Modifier un étudiant' : 'Ajouter un étudiant' }}</h1>

    @include('admin.partials.nav')

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST"
                  action="{{ $student->exists ? route('admin.etudiants.update', $student) : route('admin.etudiants.store') }}"
                  novalidate>
                @csrf
                @if ($student->exists)
                    @method('PUT')
                @endif

                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="ine" class="form-label">INE</label>
                        <input type="text" id="ine" name="ine" class="form-control @error('ine') is-invalid @enderror"
                               value="{{ old('ine', $student->ine) }}" required>
                        @error('ine')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" id="nom" name="nom" class="form-control @error('nom') is-invalid @enderror"
                               value="{{ old('nom', $student->nom) }}" required>
                        @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="prenom" class="form-label">Prénom</label>
                        <input type="text" id="prenom" name="prenom" class="form-control @error('prenom') is-invalid @enderror"
                               value="{{ old('prenom', $student->prenom) }}" required>
                        @error('prenom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $student->email) }}">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="telephone" class="form-label">Téléphone</label>
                        <input type="text" id="telephone" name="telephone" class="form-control @error('telephone') is-invalid @enderror"
                               value="{{ old('telephone', $student->telephone) }}">
                        @error('telephone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="filiere_id" class="form-label">Filière</label>
                        <select id="filiere_id" name="filiere_id" class="form-select @error('filiere_id') is-invalid @enderror">
                            <option value="">—</option>
                            @foreach ($filieres as $filiere)
                                <option value="{{ $filiere->id }}" @selected((int) old('filiere_id', $student->filiere_id) === $filiere->id)>
                                    {{ $filiere->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('filiere_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="niveau_id" class="form-label">Niveau</label>
                        <select id="niveau_id" name="niveau_id" class="form-select @error('niveau_id') is-invalid @enderror">
                            <option value="">—</option>
                            @foreach ($niveaux as $niveau)
                                <option value="{{ $niveau->id }}" @selected((int) old('niveau_id', $student->niveau_id) === $niveau->id)>
                                    {{ $niveau->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('niveau_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="promotion" class="form-label">Promotion</label>
                        <input type="text" id="promotion" name="promotion" class="form-control @error('promotion') is-invalid @enderror"
                               value="{{ old('promotion', $student->promotion) }}">
                        @error('promotion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    <a href="{{ route('admin.etudiants.index') }}" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection
