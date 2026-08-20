<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Registration;
use App\Services\ReceiptService;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class ReceiptController extends Controller
{
    public function __construct(private readonly ReceiptService $recus) {}

    public function __invoke(): Response|RedirectResponse
    {
        $student = auth()->user()->student()->firstOrFail();
        $anneeActive = AcademicYear::active();

        $registration = $anneeActive === null
            ? null
            : $student->registrations()->where('academic_year_id', $anneeActive->id)->first();

        if ($registration === null || $registration->statut !== Registration::STATUT_VALIDEE) {
            return redirect()->route('student.inscription.show')
                ->with('erreur', "Le reçu n'est disponible qu'une fois votre inscription validée.");
        }

        return $this->recus->pdf($registration)->download($this->recus->nomFichier($registration));
    }
}
