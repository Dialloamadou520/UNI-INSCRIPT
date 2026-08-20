<?php

namespace App\Imports;

class ResultatImport
{
    public int $importes = 0;

    public int $doublons = 0;

    /**
     * @var array<int, string>
     */
    public array $erreurs = [];

    public function erreur(int $ligne, string $message): void
    {
        $this->erreurs[] = "Ligne {$ligne} : {$message}";
    }

    public function total(): int
    {
        return $this->importes + $this->doublons + count($this->erreurs);
    }
}
