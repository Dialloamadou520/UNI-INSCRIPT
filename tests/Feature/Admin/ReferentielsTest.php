<?php

namespace Tests\Feature\Admin;

use App\Models\AcademicYear;
use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\Registration;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReferentielsTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->admin()->create();
    }

    public function test_un_etudiant_ne_peut_pas_gerer_les_referentiels(): void
    {
        $student = Student::factory()->for(User::factory()->etudiant())->create();

        $this->actingAs($student->user)->get(route('admin.filieres.index'))->assertForbidden();
        $this->actingAs($student->user)->get(route('admin.niveaux.index'))->assertForbidden();
        $this->actingAs($student->user)->get(route('admin.annees.index'))->assertForbidden();
    }

    public function test_l_administrateur_gere_les_filieres(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->post(route('admin.filieres.store'), ['nom' => 'Informatique', 'code' => 'info'])
            ->assertRedirect(route('admin.filieres.index'));

        $filiere = Filiere::where('nom', 'Informatique')->firstOrFail();
        $this->assertSame('INFO', $filiere->code);

        $this->actingAs($admin)
            ->put(route('admin.filieres.update', $filiere), ['nom' => 'Génie logiciel', 'code' => 'GL']);

        $this->assertSame('Génie logiciel', $filiere->fresh()->nom);

        $this->actingAs($admin)->delete(route('admin.filieres.destroy', $filiere));
        $this->assertDatabaseMissing('filieres', ['id' => $filiere->id]);
    }

    public function test_une_filiere_rattachee_a_des_etudiants_n_est_pas_supprimable(): void
    {
        $filiere = Filiere::factory()->create();
        Student::factory()->create(['filiere_id' => $filiere->id]);

        $this->actingAs($this->admin())
            ->delete(route('admin.filieres.destroy', $filiere))
            ->assertSessionHas('erreur');

        $this->assertDatabaseHas('filieres', ['id' => $filiere->id]);
    }

    public function test_un_niveau_rattache_a_des_etudiants_n_est_pas_supprimable(): void
    {
        $niveau = Niveau::factory()->create();
        Student::factory()->create(['niveau_id' => $niveau->id]);

        $this->actingAs($this->admin())
            ->delete(route('admin.niveaux.destroy', $niveau))
            ->assertSessionHas('erreur');

        $this->assertDatabaseHas('niveaux', ['id' => $niveau->id]);
    }

    public function test_le_nom_d_une_annee_doit_respecter_le_format(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.annees.store'), ['nom' => '2025'])
            ->assertSessionHasErrors('nom');

        $this->assertDatabaseCount('academic_years', 0);
    }

    public function test_activer_une_annee_desactive_les_autres(): void
    {
        $ancienne = AcademicYear::factory()->actif()->create(['nom' => '2024-2025']);
        $nouvelle = AcademicYear::factory()->create(['nom' => '2025-2026']);

        $this->actingAs($this->admin())
            ->put(route('admin.annees.activer', $nouvelle))
            ->assertRedirect(route('admin.annees.index'));

        $this->assertFalse($ancienne->fresh()->actif);
        $this->assertTrue($nouvelle->fresh()->actif);
        $this->assertSame($nouvelle->id, AcademicYear::active()->id);
    }

    public function test_une_annee_portant_des_inscriptions_n_est_pas_supprimable(): void
    {
        $annee = AcademicYear::factory()->actif()->create();
        Registration::factory()->for($annee)->for(Student::factory())->create([
            'statut' => Registration::STATUT_EN_ATTENTE,
        ]);

        $this->actingAs($this->admin())
            ->delete(route('admin.annees.destroy', $annee))
            ->assertSessionHas('erreur');

        $this->assertDatabaseHas('academic_years', ['id' => $annee->id]);
    }
}
