<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'ine',
        'nom',
        'prenom',
        'email',
        'telephone',
        'filiere_id',
        'niveau_id',
        'promotion',
        'user_id',
        'date_naissance',
        'lieu_naissance',
        'sexe',
        'nationalite',
        'adresse',
        'tuteur_prenom',
        'tuteur_nom',
        'tuteur_telephone',
    ];

    protected function casts(): array
    {
        return ['date_naissance' => 'date'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function filiere(): BelongsTo
    {
        return $this->belongsTo(Filiere::class);
    }

    public function niveau(): BelongsTo
    {
        return $this->belongsTo(Niveau::class);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function getTuteurNomCompletAttribute(): string
    {
        return trim("{$this->tuteur_prenom} {$this->tuteur_nom}");
    }

    public function getNomCompletAttribute(): string
    {
        return trim("{$this->prenom} {$this->nom}");
    }

    public function dossierComplet(): bool
    {
        return $this->date_naissance !== null
            && filled($this->lieu_naissance)
            && filled($this->sexe)
            && filled($this->nationalite)
            && filled($this->adresse)
            && filled($this->telephone);
    }
}
