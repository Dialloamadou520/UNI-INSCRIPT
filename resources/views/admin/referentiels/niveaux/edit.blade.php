@extends('layouts.base')

@section('titre', 'Modifier un niveau')

@section('contenu')
    <h1 class="h3 fw-bold mb-4">Modifier le niveau</h1>

    @include('admin.partials.nav')

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.niveaux.update', $niveau) }}" novalidate>
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-8">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" id="nom" name="nom" class="form-control @error('nom') is-invalid @enderror"
                               value="{{ old('nom', $niveau->nom) }}" required>
                        @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="ordre" class="form-label">Ordre d'affichage</label>
                        <input type="number" id="ordre" name="ordre" min="0" max="99"
                               class="form-control @error('ordre') is-invalid @enderror" value="{{ old('ordre', $niveau->ordre) }}">
                        @error('ordre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    <a href="{{ route('admin.niveaux.index') }}" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection
