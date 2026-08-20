<?php

namespace Tests\Feature\Auth;

use App\Models\Student;
use App\Models\User;
use App\Services\IneVerificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IneVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_page_de_verification_est_affichee(): void
    {
        $this->get(route('ine.verification'))->assertOk();
    }

    public function test_un_ine_inconnu_est_refuse(): void
    {
        $response = $this->from(route('ine.verification'))
            ->post(route('ine.verification.store'), ['ine' => 'INCONNU']);

        $response->assertRedirect(route('ine.verification'));
        $response->assertSessionHasErrors(['ine' => IneVerificationService::MESSAGE_INTROUVABLE]);
        $this->assertNull(session(IneVerificationService::SESSION_KEY));
    }

    public function test_un_ine_deja_rattache_a_un_compte_est_refuse(): void
    {
        $student = Student::factory()->for(User::factory())->create();

        $response = $this->from(route('ine.verification'))
            ->post(route('ine.verification.store'), ['ine' => $student->ine]);

        $response->assertSessionHasErrors(['ine' => IneVerificationService::MESSAGE_DEJA_UTILISE]);
    }

    public function test_un_ine_valide_donne_acces_au_formulaire_de_compte(): void
    {
        $student = Student::factory()->create();

        $this->post(route('ine.verification.store'), ['ine' => mb_strtolower($student->ine)])
            ->assertRedirect(route('register'));

        $this->get(route('register'))
            ->assertOk()
            ->assertSee($student->nom)
            ->assertSee($student->prenom)
            ->assertSee($student->ine);
    }

    public function test_le_formulaire_de_compte_est_inaccessible_sans_verification(): void
    {
        $this->get(route('register'))->assertRedirect(route('ine.verification'));
    }

    public function test_le_compte_cree_est_rattache_a_l_etudiant(): void
    {
        $student = Student::factory()->create(['email' => null]);

        $this->withSession([IneVerificationService::SESSION_KEY => $student->ine])
            ->post(route('register'), [
                'email' => 'etudiant@universite.sn',
                'telephone' => '770000000',
                'password' => 'MotDePasse123!',
                'password_confirmation' => 'MotDePasse123!',
            ])
            ->assertRedirect(route('student.dashboard'));

        $this->assertAuthenticated();

        $user = User::where('email', 'etudiant@universite.sn')->firstOrFail();
        $this->assertSame(User::ROLE_ETUDIANT, $user->role);
        $this->assertSame($user->id, $student->fresh()->user_id);
        $this->assertNull(session(IneVerificationService::SESSION_KEY));
    }

    public function test_un_second_compte_ne_peut_pas_utiliser_le_meme_ine(): void
    {
        $student = Student::factory()->for(User::factory())->create();

        $this->withSession([IneVerificationService::SESSION_KEY => $student->ine])
            ->post(route('register'), [
                'email' => 'autre@universite.sn',
                'telephone' => '770000000',
                'password' => 'MotDePasse123!',
                'password_confirmation' => 'MotDePasse123!',
            ])
            ->assertRedirect(route('ine.verification'));

        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => 'autre@universite.sn']);
    }
}
