@props(['statut'])

@php
    $classes = [
        \App\Models\Registration::STATUT_EN_ATTENTE => 'text-bg-secondary',
        \App\Models\Registration::STATUT_EN_COURS_VERIFICATION => 'text-bg-info',
        \App\Models\Registration::STATUT_CORRECTION_DEMANDEE => 'text-bg-warning',
        \App\Models\Registration::STATUT_VALIDEE => 'text-bg-success',
        \App\Models\Registration::STATUT_REJETEE => 'text-bg-danger',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'badge '.($classes[$statut] ?? 'text-bg-light')]) }}>
    {{ \App\Models\Registration::STATUTS[$statut] ?? $statut }}
</span>
