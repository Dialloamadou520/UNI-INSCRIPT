<?php

namespace Tests\Feature\Admin;

use App\Models\Filiere;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class StudentImportTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->admin()->create();
    }

    private function fichier(string $contenu): UploadedFile
    {
        $chemin = tempnam(sys_get_temp_dir(), 'import').'.csv';
        file_put_contents($chemin, $contenu);

        return new UploadedFile($chemin, 'etudiants.csv', 'text/csv', null, true);
    }

    public function test_l_import_cree_les_etudiants_ignore_les_doublons_et_signale_les_erreurs(): void
    {
        Student::factory()->create(['ine' => 'INE2025001']);

        $csv = <<<'CSV'
        ine,nom,prenom,email,telephone,filiere,niveau,promotion
        INE2025001,Diallo,Amadou,amadou@universite.sn,770000000,Informatique,Licence 1,2025
        INE2025002,Ba,Fatou,fatou@universite.sn,771111111,Informatique,Licence 1,2025
        ,Sow,Moussa,moussa@universite.sn,772222222,Gestion,Licence 2,2025
        CSV;

        $this->actingAs($this->admin())
            ->post(route('admin.etudiants.import.store'), ['fichier' => $this->fichier($csv)])
            ->assertRedirect(route('admin.etudiants.import'))
            ->assertSessionHas('status', '1 étudiant(s) importé(s), 1 doublon(s) ignoré(s).');

        $this->assertDatabaseHas('students', ['ine' => 'INE2025002', 'nom' => 'Ba']);
        $this->assertDatabaseMissing('students', ['nom' => 'Sow']);
        $this->assertCount(1, session('erreursImport'));

        // La filière du fichier est créée à la volée et rattachée à l'étudiant.
        $this->assertSame('Informatique', Student::where('ine', 'INE2025002')->first()->filiere->nom);
    }

    public function test_l_import_refuse_un_fichier_non_tabulaire(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.etudiants.import.store'), [
                'fichier' => UploadedFile::fake()->create('liste.pdf', 10, 'application/pdf'),
            ])
            ->assertSessionHasErrors('fichier');

        $this->assertDatabaseCount('students', 0);
    }

    public function test_le_modele_csv_est_telechargeable(): void
    {
        $reponse = $this->actingAs($this->admin())->get(route('admin.etudiants.modele'));

        $reponse->assertOk()->assertDownload('modele-import-etudiants.csv');
        $this->assertStringContainsString('ine,nom,prenom', $reponse->streamedContent());
    }

    public function test_un_etudiant_ne_peut_pas_importer(): void
    {
        $student = Student::factory()->for(User::factory()->etudiant())->create();

        $this->actingAs($student->user)
            ->get(route('admin.etudiants.import'))
            ->assertForbidden();
    }

    public function test_l_administrateur_gere_les_etudiants(): void
    {
        $filiere = Filiere::factory()->create();
        $admin = $this->admin();

        $this->actingAs($admin)
            ->post(route('admin.etudiants.store'), [
                'ine' => 'ine2025900',
                'nom' => 'Ndiaye',
                'prenom' => 'Aïssatou',
                'filiere_id' => $filiere->id,
                'promotion' => '2025',
            ])
            ->assertRedirect(route('admin.etudiants.index'));

        $student = Student::where('nom', 'Ndiaye')->firstOrFail();
        $this->assertSame('INE2025900', $student->ine);

        $this->actingAs($admin)
            ->put(route('admin.etudiants.update', $student), [
                'ine' => $student->ine,
                'nom' => 'Ndiaye',
                'prenom' => 'Aïssatou',
                'promotion' => '2026',
            ]);

        $this->assertSame('2026', $student->fresh()->promotion);

        $this->actingAs($admin)->delete(route('admin.etudiants.destroy', $student));
        $this->assertDatabaseMissing('students', ['id' => $student->id]);
    }

    public function test_un_ine_en_double_est_refuse_a_la_creation(): void
    {
        Student::factory()->create(['ine' => 'INE2025500']);

        $this->actingAs($this->admin())
            ->post(route('admin.etudiants.store'), [
                'ine' => 'INE2025500',
                'nom' => 'Fall',
                'prenom' => 'Omar',
            ])
            ->assertSessionHasErrors('ine');
    }

    public function test_un_etudiant_avec_un_compte_ne_peut_pas_etre_supprime(): void
    {
        $student = Student::factory()->for(User::factory()->etudiant())->create();

        $this->actingAs($this->admin())
            ->delete(route('admin.etudiants.destroy', $student))
            ->assertRedirect(route('admin.etudiants.index'))
            ->assertSessionHas('erreur');

        $this->assertDatabaseHas('students', ['id' => $student->id]);
    }
}
