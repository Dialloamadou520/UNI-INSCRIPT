@extends('layouts.base')

@section('titre', 'Modifier une année académique')

@section('contenu')
    <h1 class="h3 fw-bold mb-4">Modifier l'année académique</h1>

    @include('admin.partials.nav')

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.annees.update', $annee) }}" novalidate>
                @csrf
                @method('PUT')
                <div class="mb-3 col-md-4">
                    <label for="nom" class="form-label">Année</label>
                    <input type="text" id="nom" name="nom" class="form-control @error('nom') is-invalid @enderror"
                           value="{{ old('nom', $annee->nom) }}" required>
                    @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    <a href="{{ route('admin.annees.index') }}" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection
