<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Reçu d'inscription {{ $registration->numero_inscription }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #212529; }
        .entete { border-bottom: 2px solid #0d6efd; padding-bottom: 10px; margin-bottom: 20px; }
        .entete h1 { font-size: 18px; margin: 0 0 4px; color: #0d6efd; }
        .entete p { margin: 0; color: #6c757d; }
        .numero { font-size: 14px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
        th, td { text-align: left; padding: 6px 8px; border-bottom: 1px solid #dee2e6; }
        th { width: 35%; color: #6c757d; font-weight: normal; }
        .bloc-qr { text-align: center; }
        .bloc-qr img { width: 130px; height: 130px; }
        .pied { margin-top: 24px; font-size: 10px; color: #6c757d; text-align: center; }
    </style>
</head>
<body>
    <div class="entete">
        <h1>{{ config('app.name') }}</h1>
        <p>Reçu d'inscription administrative — {{ $registration->academicYear?->nom }}</p>
    </div>

    <p class="numero">Numéro d'inscription : {{ $registration->numero_inscription }}</p>

    <table>
        <tr><th>Nom et prénom</th><td>{{ $student->nom_complet }}</td></tr>
        <tr><th>INE</th><td>{{ $student->ine }}</td></tr>
        <tr><th>Date de naissance</th><td>{{ $student->date_naissance?->format('d/m/Y') ?? '—' }}</td></tr>
        <tr><th>Lieu de naissance</th><td>{{ $student->lieu_naissance ?? '—' }}</td></tr>
        <tr><th>Nationalité</th><td>{{ $student->nationalite ?? '—' }}</td></tr>
        <tr><th>Filière</th><td>{{ $student->filiere?->nom ?? '—' }}</td></tr>
        <tr><th>Niveau</th><td>{{ $student->niveau?->nom ?? '—' }}</td></tr>
        <tr><th>Promotion</th><td>{{ $student->promotion ?? '—' }}</td></tr>
        <tr><th>Année académique</th><td>{{ $registration->academicYear?->nom ?? '—' }}</td></tr>
        <tr><th>Date de validation</th><td>{{ $registration->date_validation?->format('d/m/Y à H:i') ?? '—' }}</td></tr>
    </table>

    <div class="bloc-qr">
        <img src="{{ $qrCode }}" alt="QR code de vérification">
        <p>Vérifiez l'authenticité de ce reçu : {{ $urlVerification }}</p>
    </div>

    <div class="pied">
        Document généré le {{ now()->format('d/m/Y à H:i') }} par {{ config('app.name') }} — Cellule pédagogique.
    </div>
</body>
</html>
