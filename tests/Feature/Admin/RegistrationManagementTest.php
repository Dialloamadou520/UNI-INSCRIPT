<?php

namespace Tests\Feature\Admin;

use App\Models\AcademicYear;
use App\Models\Filiere;
use App\Models\Registration;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationManagementTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->admin()->create();
    }

    private function inscription(string $statut = Registration::STATUT_EN_ATTENTE): Registration
    {
        return Registration::factory()
            ->statut($statut)
            ->for(Student::factory()->for(User::factory()->etudiant()))
            ->for(AcademicYear::factory()->actif())
            ->create();
    }

    public function test_un_etudiant_ne_peut_pas_acceder_a_la_gestion_des_inscriptions(): void
    {
        $student = Student::factory()->for(User::factory()->etudiant())->create();

        $this->actingAs($student->user)
            ->get(route('admin.inscriptions.index'))
            ->assertForbidden();
    }

    public function test_la_liste_filtre_par_statut_et_par_recherche(): void
    {
        $enAttente = $this->inscription();
        $validee = $this->inscription(Registration::STATUT_VALIDEE);

        $this->actingAs($this->admin())
            ->get(route('admin.inscriptions.index', ['statut' => Registration::STATUT_EN_ATTENTE]))
            ->assertOk()
            ->assertSee($enAttente->student->ine)
            ->assertDontSee($validee->student->ine);

        $this->actingAs($this->admin())
            ->get(route('admin.inscriptions.index', ['recherche' => $validee->student->ine]))
            ->assertOk()
            ->assertSee($validee->student->ine)
            ->assertDontSee($enAttente->student->ine);
    }

    public function test_la_liste_filtre_par_filiere(): void
    {
        $filiere = Filiere::factory()->create();
        $ciblee = $this->inscription();
        $ciblee->student->update(['filiere_id' => $filiere->id]);
        $autre = $this->inscription();

        $this->actingAs($this->admin())
            ->get(route('admin.inscriptions.index', ['filiere_id' => $filiere->id]))
            ->assertOk()
            ->assertSee($ciblee->student->ine)
            ->assertDontSee($autre->student->ine);
    }

    public function test_la_validation_genere_un_numero_et_notifie_l_etudiant(): void
    {
        $registration = $this->inscription();

        $this->actingAs($this->admin())
            ->put(route('admin.inscriptions.traiter', $registration), [
                'statut' => Registration::STATUT_VALIDEE,
            ])
            ->assertRedirect(route('admin.inscriptions.show', $registration));

        $registration->refresh();

        $this->assertSame(Registration::STATUT_VALIDEE, $registration->statut);
        $this->assertNotNull($registration->date_validation);
        $this->assertNotNull($registration->numero_inscription);
        $this->assertDatabaseHas('registration_histories', [
            'registration_id' => $registration->id,
            'action' => 'traitement',
            'nouveau_statut' => Registration::STATUT_VALIDEE,
        ]);
        $this->assertDatabaseHas('notifications', [
            'user_id' => $registration->student->user_id,
            'lu' => false,
        ]);
    }

    public function test_une_correction_exige_un_commentaire(): void
    {
        $registration = $this->inscription();

        $this->actingAs($this->admin())
            ->put(route('admin.inscriptions.traiter', $registration), [
                'statut' => Registration::STATUT_CORRECTION_DEMANDEE,
            ])
            ->assertSessionHasErrors('commentaire');

        $this->assertSame(Registration::STATUT_EN_ATTENTE, $registration->fresh()->statut);
    }

    public function test_une_correction_commentee_rouvre_le_dossier_a_l_etudiant(): void
    {
        $registration = $this->inscription();

        $this->actingAs($this->admin())
            ->put(route('admin.inscriptions.traiter', $registration), [
                'statut' => Registration::STATUT_CORRECTION_DEMANDEE,
                'commentaire' => 'Adresse illisible',
            ]);

        $registration->refresh();

        $this->assertSame('Adresse illisible', $registration->commentaire_admin);
        $this->assertTrue($registration->estModifiable());

        $this->actingAs($registration->student->user)
            ->get(route('student.inscription.edit'))
            ->assertOk()
            ->assertSee('Adresse illisible');
    }

    public function test_un_rejet_bloque_la_modification_du_dossier(): void
    {
        $registration = $this->inscription();

        $this->actingAs($this->admin())
            ->put(route('admin.inscriptions.traiter', $registration), [
                'statut' => Registration::STATUT_REJETEE,
                'commentaire' => 'Dossier non conforme',
            ]);

        $this->assertFalse($registration->fresh()->estModifiable());

        $this->actingAs($registration->student->user)
            ->get(route('student.inscription.edit'))
            ->assertRedirect(route('student.inscription.show'));
    }
}
