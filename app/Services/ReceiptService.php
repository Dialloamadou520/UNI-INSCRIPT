<?php

namespace App\Services;

use App\Models\Registration;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ReceiptService
{
    public function pdf(Registration $registration): DomPdf
    {
        $registration->loadMissing(['student.filiere', 'student.niveau', 'academicYear']);

        return Pdf::loadView('pdf.recu', [
            'registration' => $registration,
            'student' => $registration->student,
            'qrCode' => $this->qrCode($registration),
            'urlVerification' => $this->urlVerification($registration),
        ])->setPaper('a4');
    }

    public function nomFichier(Registration $registration): string
    {
        return 'recu-inscription-'.($registration->numero_inscription ?? $registration->id).'.pdf';
    }

    public function urlVerification(Registration $registration): string
    {
        return route('verification.recu', $registration->numero_inscription);
    }

    /**
     * QR code SVG encodé en data URI : DomPDF le rend sans dépendre d'Imagick.
     */
    private function qrCode(Registration $registration): string
    {
        $svg = QrCode::format('svg')
            ->size(140)
            ->margin(0)
            ->generate($this->urlVerification($registration));

        return 'data:image/svg+xml;base64,'.base64_encode((string) $svg);
    }
}
