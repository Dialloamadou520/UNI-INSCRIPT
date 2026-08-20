@extends('layouts.base')

@section('titre', 'Tableau de bord administrateur')

@section('contenu')
    <h1 class="h3 fw-bold mb-4">Tableau de bord</h1>

    @include('admin.partials.nav')

    <div class="row g-3">
        @foreach ([
            ['libelle' => 'Étudiants', 'valeur' => $nombreEtudiants, 'icone' => 'people'],
            ['libelle' => 'Demandes en attente', 'valeur' => $parStatut['en_attente'] ?? 0, 'icone' => 'hourglass-split'],
            ['libelle' => 'Inscriptions validées', 'valeur' => $parStatut['validee'] ?? 0, 'icone' => 'check-circle'],
            ['libelle' => 'Inscriptions rejetées', 'valeur' => $parStatut['rejetee'] ?? 0, 'icone' => 'x-circle'],
            ['libelle' => 'Corrections demandées', 'valeur' => $parStatut['correction_demandee'] ?? 0, 'icone' => 'pencil-square'],
        ] as $carte)
            <div class="col-6 col-lg-4">
                <div class="card card-stat border-0 shadow-sm h-100">
                    <div class="card-body">
                        <i class="bi bi-{{ $carte['icone'] }} fs-4 text-primary"></i>
                        <div class="display-6">{{ $carte['valeur'] }}</div>
                        <div class="text-muted">{{ $carte['libelle'] }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div>
                <h2 class="h6 mb-1">Demandes à traiter</h2>
                <p class="text-muted small mb-0">Consultez les dossiers, validez, rejetez ou demandez une correction.</p>
            </div>
            <a href="{{ route('admin.inscriptions.index') }}" class="btn btn-primary">Gérer les inscriptions</a>
        </div>
    </div>
@endsection
