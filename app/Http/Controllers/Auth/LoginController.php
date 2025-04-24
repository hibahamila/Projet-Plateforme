<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\AccountValidationMail;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    protected $maxAttempts = 3; // Default is 5
    protected $decayMinutes = 2;

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        // Vérifier si l'utilisateur existe
        $user = User::where('email', $request->email)->first();

        // Si l'utilisateur n'existe pas, rediriger avec une erreur
        if (!$user) {
            return $this->sendFailedLoginResponse($request);
        }

        // Vérifier si l'utilisateur est inactif (bloqué par l'administration) et n'a pas de code de validation
        if ($user->status === 'inactive' && $user->validation_code === null) {
            return redirect()->back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors([
                    'email' => 'Vous n\'avez pas accès à cette plateforme.',
                ]);
        }

        // Vérification spécifique pour les étudiants avec un code de validation non validé
        if ($user->hasRole('etudiant') && $user->validation_code !== null) {
            // Générer un nouveau code de validation
            $validationCode = Str::random(6);
            $user->validation_code = $validationCode;
            $user->save();

            try {
                session(['user_id' => $user->id]);
                Mail::to($user->email)->send(new AccountValidationMail($user, $validationCode));
                return redirect()->route('validation.form')->with('info', 'Votre compte n\'est pas encore activé. Un nouveau code de validation a été envoyé à votre email.');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'envoi de l\'e-mail de validation.');
            }
        }
        // Vérification spécifique pour les autres autilisateur avec un code de validation
        if ( $user->validation_code !== null) {
            $request->session()->put('user_id', $user->id);
            return redirect()->route('validation.form')->with('info', 'Votre compte n\'est pas encore activé. Veuillez saisir le code de validation reçu par email.');

        }

        // Si l'utilisateur est valide, tenter de le connecter
        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // Si la connexion échoue, rediriger avec une erreur
        return $this->sendFailedLoginResponse($request);
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // Rediriger l'utilisateur vers la page de modification de mot de passe si c'est sa première connexion
        if ($user->first_login && $user->validation_code === null) {
            return redirect()->route('password.change.form');
        }



        // Rediriger les autres utilisateurs vers le dashboard
        return redirect()->route('dashboard.index');
    }
}
