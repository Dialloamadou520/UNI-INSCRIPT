<?php

namespace Tests\Feature\Student;

use App\Models\AcademicYear;
use App\Models\Registration;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReceiptAndNotificationTest extends TestCase
{
    use RefreshDatabase;

    private function inscription(string $statut, ?string $numero = null): Registration
    {
        return Registration::factory()
            ->statut($statut)
            ->for(Student::factory()->for(User::factory()->etudiant()))
            ->for(AcademicYear::factory()->actif())
            ->create([
                'numero_inscription' => $numero,
                'date_validation' => $statut === Registration::STATUT_VALIDEE ? now() : null,
            ]);
    }

    public function test_le_recu_est_telechargeable_apres_validation(): void
    {
        $registration = $this->inscription(Registration::STATUT_VALIDEE, 'INS-2025-2026-000001');

        $reponse = $this->actingAs($registration->student->user)->get(route('student.inscription.recu'));

        $reponse->assertOk()
            ->assertDownload('recu-inscription-INS-2025-2026-000001.pdf');
        $this->assertStringStartsWith('%PDF-', $reponse->getContent());
    }

    public function test_le_recu_est_refuse_tant_que_l_inscription_n_est_pas_validee(): void
    {
        $registration = $this->inscription(Registration::STATUT_EN_ATTENTE);

        $this->actingAs($registration->student->user)
            ->get(route('student.inscription.recu'))
            ->assertRedirect(route('student.inscription.show'))
            ->assertSessionHas('erreur');
    }

    public function test_la_page_publique_de_verification_confirme_un_recu_valide(): void
    {
        $registration = $this->inscription(Registration::STATUT_VALIDEE, 'INS-2025-2026-000002');

        $this->get(route('verification.recu', $registration->numero_inscription))
            ->assertOk()
            ->assertSee('Reçu authentique')
            ->assertSee($registration->student->ine);
    }

    public function test_la_page_publique_rejette_un_numero_inconnu(): void
    {
        $this->get(route('verification.recu', 'INS-FAUX-000000'))
            ->assertOk()
            ->assertSee('Aucun reçu valide');
    }

    public function test_l_etudiant_consulte_et_marque_ses_notifications(): void
    {
        $student = Student::factory()->for(User::factory()->etudiant())->create();
        $notification = $student->user->notificationsInternes()->create([
            'titre' => 'Inscription : Validée',
            'message' => 'Votre inscription est validée.',
            'lu' => false,
        ]);

        $this->actingAs($student->user)
            ->get(route('student.notifications.index'))
            ->assertOk()
            ->assertSee('Votre inscription est validée.');

        $this->actingAs($student->user)
            ->put(route('student.notifications.lue', $notification))
            ->assertRedirect();

        $this->assertTrue($notification->fresh()->lu);
    }

    public function test_un_etudiant_ne_peut_pas_marquer_la_notification_d_un_autre(): void
    {
        $autre = Student::factory()->for(User::factory()->etudiant())->create();
        $notification = $autre->user->notificationsInternes()->create([
            'titre' => 'Inscription : Validée',
            'message' => 'Votre inscription est validée.',
            'lu' => false,
        ]);

        $student = Student::factory()->for(User::factory()->etudiant())->create();

        $this->actingAs($student->user)
            ->put(route('student.notifications.lue', $notification))
            ->assertForbidden();

        $this->assertFalse($notification->fresh()->lu);
    }
}
