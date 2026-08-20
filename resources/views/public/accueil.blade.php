@extends('layouts.base')

@section('titre', 'Accueil')

@section('contenu')
    <div class="hero rounded-4 p-4 p-md-5 mb-4 shadow-sm">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <h1 class="display-6 fw-bold">Inscrivez-vous administrativement sans vous déplacer</h1>
                <p class="lead mb-4">
                    Vérifiez votre INE, créez votre compte, complétez votre dossier et suivez la validation
                    de votre inscription en ligne.
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('ine.verification') }}" class="btn btn-light btn-lg fw-semibold">
                        <i class="bi bi-shield-check me-1"></i> Vérifier mon INE
                    </a>
                    <a href="{{ route('comment-ca-marche') }}" class="btn btn-outline-light btn-lg">Comment ça marche</a>
                </div>
            </div>
            <div class="col-lg-5 text-center d-none d-lg-block">
                <i class="bi bi-mortarboard" style="font-size: 10rem; opacity: .25;"></i>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card card-stat border-0 shadow-sm h-100 text-center">
                <div class="card-body">
                    <div class="display-6 text-primary">{{ $nombreEtudiants }}</div>
                    <div class="text-muted">Étudiants référencés</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-stat border-0 shadow-sm h-100 text-center">
                <div class="card-body">
                    <div class="display-6 text-primary">{{ $nombreFilieres }}</div>
                    <div class="text-muted">Filières</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-stat border-0 shadow-sm h-100 text-center">
                <div class="card-body">
                    <div class="display-6 text-primary">100 %</div>
                    <div class="text-muted">Démarches en ligne</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <i class="bi bi-shield-check fs-3 text-primary"></i>
                    <h2 class="h5 mt-2">Vérification par INE</h2>
                    <p class="text-muted mb-0">Seuls les étudiants figurant dans la liste officielle peuvent créer un compte.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <i class="bi bi-clock-history fs-3 text-primary"></i>
                    <h2 class="h5 mt-2">Suivi en temps réel</h2>
                    <p class="text-muted mb-0">Consultez le statut de votre dossier et les demandes de correction.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <i class="bi bi-file-earmark-pdf fs-3 text-primary"></i>
                    <h2 class="h5 mt-2">Reçu certifié</h2>
                    <p class="text-muted mb-0">Téléchargez un reçu PDF avec numéro unique et QR Code de vérification.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
