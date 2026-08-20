<?php

namespace Tests\Feature\Student;

use App\Models\AcademicYear;
use App\Models\Registration;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationDossierTest extends TestCase
{
    use RefreshDatabase;

    private function etudiant(): Student
    {
        return Student::factory()->for(User::factory()->etudiant())->create();
    }

    /**
     * @return array<string, string>
     */
    private function dossier(): array
    {
        return [
            'date_naissance' => '2002-05-14',
            'lieu_naissance' => 'Saint-Louis',
            'sexe' => 'Masculin',
            'nationalite' => 'Sénégalaise',
            'adresse' => 'Quartier Nord, Saint-Louis',
            'telephone' => '770000000',
            'email' => 'etudiant@universite.sn',
        ];
    }

    public function test_le_dossier_soumis_passe_en_attente_et_est_journalise(): void
    {
        $annee = AcademicYear::factory()->actif()->create();
        $student = $this->etudiant();

        $this->actingAs($student->user)
            ->put(route('student.inscription.update'), $this->dossier())
            ->assertRedirect(route('student.inscription.show'));

        $registration = Registration::where('student_id', $student->id)->firstOrFail();

        $this->assertSame($annee->id, $registration->academic_year_id);
        $this->assertSame(Registration::STATUT_EN_ATTENTE, $registration->statut);
        $this->assertNotNull($registration->date_soumission);
        $this->assertSame('Saint-Louis', $student->fresh()->lieu_naissance);
        $this->assertDatabaseHas('registration_histories', [
            'registration_id' => $registration->id,
            'action' => 'soumission',
            'nouveau_statut' => Registration::STATUT_EN_ATTENTE,
        ]);
    }

    public function test_le_dossier_incomplet_est_rejete_par_la_validation(): void
    {
        AcademicYear::factory()->actif()->create();
        $student = $this->etudiant();

        $this->actingAs($student->user)
            ->put(route('student.inscription.update'), ['sexe' => 'Autre'])
            ->assertSessionHasErrors(['date_naissance', 'lieu_naissance', 'sexe', 'nationalite', 'adresse']);

        $this->assertDatabaseCount('registrations', 0);
    }

    public function test_un_dossier_valide_n_est_plus_modifiable(): void
    {
        $annee = AcademicYear::factory()->actif()->create();
        $student = $this->etudiant();
        Registration::create([
            'student_id' => $student->id,
            'academic_year_id' => $annee->id,
            'statut' => Registration::STATUT_VALIDEE,
        ]);

        $this->actingAs($student->user)
            ->get(route('student.inscription.edit'))
            ->assertRedirect(route('student.inscription.show'));

        $this->actingAs($student->user)
            ->put(route('student.inscription.update'), $this->dossier())
            ->assertRedirect(route('student.inscription.show'));

        $this->assertNull($student->fresh()->lieu_naissance);
    }

    public function test_une_correction_demandee_peut_etre_resoumise(): void
    {
        $annee = AcademicYear::factory()->actif()->create();
        $student = $this->etudiant();
        $registration = Registration::create([
            'student_id' => $student->id,
            'academic_year_id' => $annee->id,
            'statut' => Registration::STATUT_CORRECTION_DEMANDEE,
            'commentaire_admin' => 'Adresse illisible',
        ]);

        $this->actingAs($student->user)->get(route('student.inscription.edit'))->assertOk();

        $this->actingAs($student->user)->put(route('student.inscription.update'), $this->dossier());

        $this->assertSame(Registration::STATUT_EN_ATTENTE, $registration->fresh()->statut);
        $this->assertDatabaseHas('registration_histories', [
            'registration_id' => $registration->id,
            'action' => 'resoumission',
            'ancien_statut' => Registration::STATUT_CORRECTION_DEMANDEE,
        ]);
    }

    public function test_un_etudiant_ne_voit_pas_le_dossier_d_un_autre(): void
    {
        $annee = AcademicYear::factory()->actif()->create();
        $autre = $this->etudiant();
        Registration::create([
            'student_id' => $autre->id,
            'academic_year_id' => $annee->id,
            'statut' => Registration::STATUT_VALIDEE,
        ]);

        $student = $this->etudiant();

        $this->actingAs($student->user)
            ->get(route('student.inscription.show'))
            ->assertOk()
            ->assertDontSee($autre->ine);
    }

    public function test_le_profil_permet_de_mettre_a_jour_les_coordonnees(): void
    {
        $student = $this->etudiant();

        $this->actingAs($student->user)
            ->put(route('student.profil.contact'), [
                'email' => 'nouvel@universite.sn',
                'telephone' => '771111111',
            ])
            ->assertSessionHasNoErrors();

        $this->assertSame('nouvel@universite.sn', $student->user->fresh()->email);
        $this->assertSame('771111111', $student->fresh()->telephone);
    }
}
