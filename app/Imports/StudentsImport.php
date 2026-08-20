<?php

namespace App\Imports;

use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\Student;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToCollection, WithHeadingRow
{
    /**
     * Colonnes attendues dans le fichier fourni par l'université.
     */
    public const COLONNES = ['ine', 'nom', 'prenom', 'email', 'telephone', 'filiere', 'niveau', 'promotion'];

    public function __construct(public readonly ResultatImport $resultat = new ResultatImport) {}

    /**
     * @param  Collection<int, Collection<string, mixed>>  $rows
     */
    public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $row) {
            $ligne = $index + 2; // en-tête sur la première ligne
            $donnees = $this->normaliser($row);

            if ($donnees === null) {
                continue;
            }

            $validation = Validator::make($donnees, [
                'ine' => ['required', 'string', 'max:50'],
                'nom' => ['required', 'string', 'max:255'],
                'prenom' => ['required', 'string', 'max:255'],
                'email' => ['nullable', 'email', 'max:255'],
                'telephone' => ['nullable', 'string', 'max:30'],
                'promotion' => ['nullable', 'string', 'max:20'],
            ]);

            if ($validation->fails()) {
                $this->resultat->erreur($ligne, implode(' ', $validation->errors()->all()));

                continue;
            }

            if (Student::where('ine', $donnees['ine'])->exists()) {
                $this->resultat->doublons++;

                continue;
            }

            Student::create([
                ...$validation->validated(),
                'filiere_id' => $this->filiere($donnees['filiere']),
                'niveau_id' => $this->niveau($donnees['niveau']),
            ]);

            $this->resultat->importes++;
        }
    }

    public function headingRow(): int
    {
        return 1;
    }

    /**
     * @param  Collection<string, mixed>  $row
     * @return array<string, string|null>|null
     */
    private function normaliser(Collection $row): ?array
    {
        $valeurs = [];

        foreach (self::COLONNES as $colonne) {
            $valeur = $row->get($colonne);
            $valeurs[$colonne] = is_scalar($valeur) ? trim((string) $valeur) : null;
            $valeurs[$colonne] = $valeurs[$colonne] === '' ? null : $valeurs[$colonne];
        }

        if (collect($valeurs)->filter()->isEmpty()) {
            return null; // ligne vide ignorée
        }

        $valeurs['ine'] = $valeurs['ine'] === null ? null : Str::upper($valeurs['ine']);

        return $valeurs;
    }

    private function filiere(?string $nom): ?int
    {
        if ($nom === null) {
            return null;
        }

        return Filiere::firstOrCreate(
            ['nom' => $nom],
            ['code' => Str::upper(Str::substr(Str::slug($nom, ''), 0, 10))],
        )->id;
    }

    private function niveau(?string $nom): ?int
    {
        if ($nom === null) {
            return null;
        }

        return Niveau::firstOrCreate(['nom' => $nom])->id;
    }
}
