<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Services\IneVerificationService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function __construct(private readonly IneVerificationService $verification) {}

    /**
     * Formulaire de création de compte, accessible uniquement après vérification de l'INE.
     */
    public function create(Request $request): View|RedirectResponse
    {
        $student = $this->etudiantVerifie($request);

        if ($student === null) {
            return redirect()->route('ine.verification');
        }

        return view('auth.register', ['student' => $student]);
    }

    public function store(Request $request): RedirectResponse
    {
        $student = $this->etudiantVerifie($request);

        if ($student === null) {
            return redirect()->route('ine.verification');
        }

        $data = $request->validate([
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'telephone' => ['required', 'string', 'max:30'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = DB::transaction(function () use ($data, $student) {
            $user = User::create([
                'name' => $student->nom_complet,
                'email' => $data['email'],
                'telephone' => $data['telephone'],
                'password' => $data['password'],
                'role' => User::ROLE_ETUDIANT,
            ]);

            $student->update([
                'user_id' => $user->id,
                'email' => $student->email ?: $data['email'],
                'telephone' => $data['telephone'],
            ]);

            return $user;
        });

        $request->session()->forget(IneVerificationService::SESSION_KEY);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('student.dashboard');
    }

    /**
     * Étudiant dont l'INE a été vérifié dans cette session, s'il est toujours disponible.
     */
    private function etudiantVerifie(Request $request): ?Student
    {
        $ine = $request->session()->get(IneVerificationService::SESSION_KEY);

        if (! is_string($ine)) {
            return null;
        }

        $student = $this->verification->trouver($ine);

        if ($student === null || $this->verification->possedeDejaUnCompte($student)) {
            return null;
        }

        return $student;
    }
}
