<?php

namespace Database\Seeders;

use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $etudiants = [
            ['ine' => 'INE2025001', 'nom' => 'Diallo', 'prenom' => 'Amadou', 'filiere' => 'INFO', 'niveau' => 'Licence 1'],
            ['ine' => 'INE2025002', 'nom' => 'Ndiaye', 'prenom' => 'Fatou', 'filiere' => 'GEST', 'niveau' => 'Licence 2'],
            ['ine' => 'INE2025003', 'nom' => 'Sow', 'prenom' => 'Moussa', 'filiere' => 'DROIT', 'niveau' => 'Master 1'],
        ];

        foreach ($etudiants as $etudiant) {
            Student::firstOrCreate(
                ['ine' => $etudiant['ine']],
                [
                    'nom' => $etudiant['nom'],
                    'prenom' => $etudiant['prenom'],
                    'filiere_id' => Filiere::where('code', $etudiant['filiere'])->value('id'),
                    'niveau_id' => Niveau::where('nom', $etudiant['niveau'])->value('id'),
                    'promotion' => '2025',
                ]
            );
        }
    }
}
