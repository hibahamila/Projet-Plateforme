<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Mail\AccountValidationMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Propaganistas\LaravelPhone\PhoneNumber;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    //tkhali l'utilisateur eli mahouch connecté yhezou l'inscription et l'utilisateur connecté yhezou l paage par defaut
    public function __construct()
    {
        $this->middleware('guest');
    }


    public function showRegistrationForm()
    {
        return view('admin.authentication.sign-up');
    }

    
    public function register(Request $request)
    {
        // Formater d'abord le numéro de téléphone
        $countryCode = '+216';
        $localNumber = $request->input('phone');
        $fullPhoneNumber = $countryCode . ' ' . $localNumber;
        $formattedPhoneNumber = PhoneNumber::make($fullPhoneNumber, 'TN')->formatE164();

        // Vérifier manuellement si ce numéro formaté existe déjà
        $existingUser = User::where('phone', $formattedPhoneNumber)->first();
        if ($existingUser) {
            return back()->withErrors(['phone' => 'Ce numéro de téléphone est déjà associé à un compte existant.'])->withInput();
        }

        $messages = [
            'email.unique' => 'Cet email est déjà utilisé par un autre utilisateur.',
            'phone.phone' => 'Le numéro de téléphone doit être valide pour la Tunisie.',
            'password.min' => 'La longueur du mot de passe doit être d\'au moins 8 caractères.',
            'privacy_policy.required' => 'Vous devez accepter la politique de confidentialité.',
        ];

        $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|phone:TN', // Sans le unique car on le vérifie manuellement
            'password' => 'required|string|min:8',
            'privacy_policy' => 'required',
        ], $messages);

        $validationCode = Str::random(6);

        try {
            $user = User::create([
                'name' => $request->name,
                'lastname' => $request->lastname,
                'email' => $request->email,
                'phone' => $formattedPhoneNumber, // Utiliser le numéro formaté
                'password' => Hash::make($request->password),
                'validation_code' => $validationCode,
                'status' => 'inactive',
                'first_login' => false,
            ]);

            $request->session()->put('user_id', $user->id);
            $user->assignRole('etudiant');

            Mail::to($user->email)->send(new AccountValidationMail($user, $validationCode));

            return redirect()->route('validation.form')->with('success', 'Un code de validation a été envoyé à votre email.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
    }
    public function showValidationForm()
    {
        if (!session('user_id')) {
            return redirect()->route('login')->with('error', 'Session expirée. Veuillez vous connecter à nouveau.');
        }
        return view('auth.verify');
    }
    public function validateAccount(Request $request)
    {
        $request->validate([
            'validation_code' => 'required|string',
        ]);

        $userId = session('user_id');


        if (!$userId) {
            return redirect()->route('login')
                ->with('error', 'Votre session a expiré. Veuillez vous connecter à nouveau.');
        }

        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('sign-up')
                ->with('error', 'Utilisateur non trouvé. Veuillez vous connecter à nouveau.');
        }

        if ($user->validation_code !== $request->validation_code) {
            return back()->with('error', 'Code de validation incorrect.');
        }


        $user->status = 'active';
        $user->validation_code = null;
        $user->email_verified_at = now();
        $user->save();

        // Supprimer l'ID de la session après validation

         session()->forget('user_id');


        auth()->login($user);
        if ($user->first_login) {
            return redirect()->route('password.change.form');
        }
        return redirect()->route('index')
            ->with('success', 'Votre compte a été activé avec succès.');
    }
    public function resendCode(Request $request)
    {
        // Récupérer l'ID utilisateur de la session
        $userId = $request->session()->get('user_id');

        if (!$userId) {
            return redirect()->route('sign-up')
                ->with('error', 'Votre session a expiré. Veuillez vous inscrire à nouveau.');
        }

        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('sign-up')
                ->with('error', 'Utilisateur non trouvé. Veuillez vous inscrire à nouveau.');
        }


        $validationCode = Str::random(6);
        $user->validation_code = $validationCode;
        $user->save();


        try {
            Mail::to($user->email)->send(new AccountValidationMail($user,$validationCode));
        } catch (\Exception $e) {
            \Log::error('Erreur d\'envoi d\'email: ' . $e->getMessage());
            return back()->with('warning', 'Problème lors de l\'envoi de l\'email. Votre nouveau code est: ' . $validationCode);
        }

        return back()->with('success', 'Un nouveau code de validation a été envoyé à votre email.');
    }

}

