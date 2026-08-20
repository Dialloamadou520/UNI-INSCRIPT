<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use Illuminate\Database\Seeder;

class AcademicYearSeeder extends Seeder
{
    public function run(): void
    {
        AcademicYear::firstOrCreate(['nom' => '2024-2025'], ['actif' => false]);
        AcademicYear::firstOrCreate(['nom' => '2025-2026'], ['actif' => true]);
    }
}
