@extends('layouts.guest')

@section('titre', 'Vérification de l\'INE')
@section('titre_carte', 'Vérification de votre INE')
@section('sous_titre', "Saisissez votre INE pour vérifier que vous figurez dans la liste officielle de l'université.")

@section('formulaire')
    <form method="POST" action="{{ route('ine.verification.store') }}" novalidate>
        @csrf

        <div class="mb-3">
            <label for="ine" class="form-label">INE</label>
            <input type="text" class="form-control form-control-lg @error('ine') is-invalid @enderror"
                   id="ine" name="ine" value="{{ old('ine') }}" required autofocus
                   placeholder="Ex. INE2025001">
            @error('ine')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100">Vérifier mon INE</button>
    </form>

    <p class="text-muted small mt-3 mb-0">
        Vous avez déjà un compte ? <a href="{{ route('login') }}">Connectez-vous</a>.
    </p>
@endsection
