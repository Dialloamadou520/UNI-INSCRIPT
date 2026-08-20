@extends('layouts.base')

@section('titre', 'La plateforme')

@section('contenu')
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4 p-md-5">
            <h1 class="h3 fw-bold mb-3">La plateforme d'inscription administrative</h1>
            <p class="text-muted">
                La plateforme dématérialise l'inscription administrative de l'université. L'administration
                importe la liste officielle des étudiants ; chaque étudiant s'identifie ensuite avec son INE
                pour créer un compte, compléter son dossier et suivre sa validation par la cellule pédagogique.
            </p>

            <h2 class="h5 mt-4">Pour les étudiants</h2>
            <ul class="text-muted">
                <li>Création de compte après vérification de l'INE.</li>
                <li>Dossier d'inscription complété en ligne, à tout moment.</li>
                <li>Suivi du statut et réponse aux demandes de correction.</li>
                <li>Reçu d'inscription téléchargeable après validation.</li>
            </ul>

            <h2 class="h5 mt-4">Pour la cellule pédagogique</h2>
            <ul class="text-muted">
                <li>Import de la liste officielle des étudiants au format CSV ou Excel.</li>
                <li>Gestion des filières, niveaux et années académiques.</li>
                <li>Validation, rejet ou demande de correction des dossiers.</li>
                <li>Statistiques et recherche avancée des inscriptions.</li>
            </ul>
        </div>
    </div>
@endsection
