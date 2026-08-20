<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Niveau extends Model
{
    use HasFactory;

    protected $table = 'niveaux';

    protected $fillable = ['nom', 'ordre'];

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
}
