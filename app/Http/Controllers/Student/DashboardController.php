<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $student = auth()->user()->student()->with(['filiere', 'niveau'])->firstOrFail();
        $anneeActive = AcademicYear::active();

        $registration = $anneeActive === null
            ? null
            : $student->registrations()->where('academic_year_id', $anneeActive->id)->first();

        return view('student.dashboard', [
            'student' => $student,
            'anneeActive' => $anneeActive,
            'registration' => $registration,
        ]);
    }
}
