<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_etudiant_est_redirige_vers_son_tableau_de_bord(): void
    {
        AcademicYear::factory()->actif()->create();
        $student = Student::factory()->for(User::factory()->etudiant())->create();

        $this->actingAs($student->user)
            ->get(route('dashboard'))
            ->assertRedirect(route('student.dashboard'));

        $this->actingAs($student->user)
            ->get(route('student.dashboard'))
            ->assertOk()
            ->assertSee($student->prenom);
    }

    public function test_un_admin_est_redirige_vers_le_tableau_de_bord_admin(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertRedirect(route('admin.dashboard'));

        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk();
    }

    public function test_un_etudiant_ne_peut_pas_acceder_a_l_espace_admin(): void
    {
        $student = Student::factory()->for(User::factory()->etudiant())->create();

        $this->actingAs($student->user)->get(route('admin.dashboard'))->assertForbidden();
    }

    public function test_un_admin_ne_peut_pas_acceder_a_l_espace_etudiant(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get(route('student.dashboard'))->assertForbidden();
    }

    public function test_les_espaces_sont_proteges_pour_les_visiteurs(): void
    {
        $this->get(route('student.dashboard'))->assertRedirect(route('login'));
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }
}
