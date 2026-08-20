@extends('layouts.base')

@section('titre', 'Tableau de bord administrateur')

@section('contenu')
    <h1 class="h3 fw-bold mb-4">Tableau de bord</h1>

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
@endsection
