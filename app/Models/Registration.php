<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Registration extends Model
{
    use HasFactory;

    public const STATUT_EN_ATTENTE = 'en_attente';

    public const STATUT_EN_COURS_VERIFICATION = 'en_cours_verification';

    public const STATUT_CORRECTION_DEMANDEE = 'correction_demandee';

    public const STATUT_VALIDEE = 'validee';

    public const STATUT_REJETEE = 'rejetee';

    public const STATUTS = [
        self::STATUT_EN_ATTENTE => 'En attente',
        self::STATUT_EN_COURS_VERIFICATION => 'En cours de vérification',
        self::STATUT_CORRECTION_DEMANDEE => 'Correction demandée',
        self::STATUT_VALIDEE => 'Validée',
        self::STATUT_REJETEE => 'Rejetée',
    ];

    protected $fillable = [
        'student_id',
        'academic_year_id',
        'numero_inscription',
        'statut',
        'date_soumission',
        'date_validation',
        'commentaire_admin',
    ];

    protected function casts(): array
    {
        return [
            'date_soumission' => 'datetime',
            'date_validation' => 'datetime',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(RegistrationHistory::class);
    }

    public function getLibelleStatutAttribute(): string
    {
        return self::STATUTS[$this->statut] ?? $this->statut;
    }

    public function estModifiable(): bool
    {
        return in_array($this->statut, [self::STATUT_EN_ATTENTE, self::STATUT_CORRECTION_DEMANDEE], true);
    }
}
