@extends('layouts.base')

@section('titre', 'Comment ça marche')

@section('contenu')
    <h1 class="h3 fw-bold mb-4">Comment ça marche</h1>

    <div class="row g-3">
        @foreach ([
            ['titre' => 'Vérifiez votre INE', 'texte' => "Saisissez votre INE : la plateforme vérifie que vous figurez dans la liste officielle de l'université."],
            ['titre' => 'Créez votre compte', 'texte' => 'Renseignez votre email, votre téléphone et votre mot de passe. Un seul compte par INE.'],
            ['titre' => 'Complétez votre dossier', 'texte' => 'Ajoutez vos informations personnelles ; les informations académiques sont pré-remplies.'],
            ['titre' => 'Suivez la validation', 'texte' => "La cellule pédagogique valide, rejette ou demande une correction. Vous êtes notifié à chaque étape."],
            ['titre' => 'Téléchargez votre reçu', 'texte' => 'Une fois validée, votre inscription donne accès à un reçu PDF avec QR Code.'],
        ] as $index => $etape)
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex gap-3">
                        <span class="etape-numero flex-shrink-0">{{ $index + 1 }}</span>
                        <div>
                            <h2 class="h6 fw-bold mb-1">{{ $etape['titre'] }}</h2>
                            <p class="text-muted mb-0">{{ $etape['texte'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="text-center mt-4">
        <a href="{{ route('ine.verification') }}" class="btn btn-primary btn-lg">Commencer maintenant</a>
    </div>
@endsection
