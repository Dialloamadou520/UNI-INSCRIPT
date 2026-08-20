<?php

namespace Database\Seeders;

use App\Models\Niveau;
use Illuminate\Database\Seeder;

class NiveauSeeder extends Seeder
{
    public function run(): void
    {
        $niveaux = ['Licence 1', 'Licence 2', 'Licence 3', 'Master 1', 'Master 2'];

        foreach ($niveaux as $ordre => $nom) {
            Niveau::firstOrCreate(['nom' => $nom], ['ordre' => $ordre + 1]);
        }
    }
}
