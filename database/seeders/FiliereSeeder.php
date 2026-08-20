<?php

namespace Database\Seeders;

use App\Models\Filiere;
use Illuminate\Database\Seeder;

class FiliereSeeder extends Seeder
{
    public function run(): void
    {
        $filieres = [
            ['nom' => 'Informatique', 'code' => 'INFO'],
            ['nom' => 'Gestion', 'code' => 'GEST'],
            ['nom' => 'Droit', 'code' => 'DROIT'],
            ['nom' => 'Lettres Modernes', 'code' => 'LETT'],
            ['nom' => 'Mathématiques Appliquées', 'code' => 'MATH'],
        ];

        foreach ($filieres as $filiere) {
            Filiere::firstOrCreate(['code' => $filiere['code']], $filiere);
        }
    }
}
