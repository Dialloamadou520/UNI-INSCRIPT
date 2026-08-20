<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\IneVerificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IneVerificationController extends Controller
{
    public function __construct(private readonly IneVerificationService $verification) {}

    public function create(): View
    {
        return view('auth.verifier-ine');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ine' => ['required', 'string', 'max:50'],
        ], [], ['ine' => 'INE']);

        $student = $this->verification->trouver($data['ine']);

        if ($student === null) {
            return back()
                ->withInput()
                ->withErrors(['ine' => IneVerificationService::MESSAGE_INTROUVABLE]);
        }

        if ($this->verification->possedeDejaUnCompte($student)) {
            return back()
                ->withInput()
                ->withErrors(['ine' => IneVerificationService::MESSAGE_DEJA_UTILISE]);
        }

        $request->session()->put(IneVerificationService::SESSION_KEY, $student->ine);

        return redirect()->route('register');
    }
}
