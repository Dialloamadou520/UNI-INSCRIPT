<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Filiere extends Model
{
    use HasFactory;

    protected $table = 'filieres';

    protected $fillable = ['nom', 'code'];

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
}
