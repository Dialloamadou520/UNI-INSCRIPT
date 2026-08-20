<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicYear extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'actif'];

    protected function casts(): array
    {
        return ['actif' => 'boolean'];
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public static function active(): ?self
    {
        return static::where('actif', true)->first();
    }
}
