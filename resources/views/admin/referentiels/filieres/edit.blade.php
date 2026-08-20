@extends('layouts.base')

@section('titre', 'Modifier une filière')

@section('contenu')
    <h1 class="h3 fw-bold mb-4">Modifier la filière</h1>

    @include('admin.partials.nav')

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.filieres.update', $filiere) }}" novalidate>
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-8">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" id="nom" name="nom" class="form-control @error('nom') is-invalid @enderror"
                               value="{{ old('nom', $filiere->nom) }}" required>
                        @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="code" class="form-label">Code</label>
                        <input type="text" id="code" name="code" class="form-control @error('code') is-invalid @enderror"
                               value="{{ old('code', $filiere->code) }}" required>
                        @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    <a href="{{ route('admin.filieres.index') }}" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection
