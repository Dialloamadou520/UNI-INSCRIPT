<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\View\View;

class ReceiptVerificationController extends Controller
{
    /**
     * Page publique atteinte via le QR code du reçu.
     */
    public function __invoke(string $numero): View
    {
        $registration = Registration::query()
            ->with(['student.filiere', 'student.niveau', 'academicYear'])
            ->where('numero_inscription', $numero)
            ->where('statut', Registration::STATUT_VALIDEE)
            ->first();

        return view('verification-recu', [
            'numero' => $numero,
            'registration' => $registration,
        ]);
    }
}
