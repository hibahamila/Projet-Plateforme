<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Mail\ResetPasswordMail;


class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */



    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetCode(Request $request){
        $messages = [
            'email.exists' => 'Aucun compte n\'est associé à cette adresse e-mail.',
            'email.required' => 'Veuillez saisir votre adresse e-mail.',
            'email.email' => 'Veuillez saisir une adresse e-mail valide.',
        ];
        $request->validate([
            'email' =>'required|email|exists:users,email',
        ],$messages);
        $user=User::where('email',$request->email)->first();

        $code=Str::random(6);
        session(['reset_email' => $user->email, 'reset_code' => $code]);

        $user->update(
            ['code_reset_password' => $code]
        );
        try {
            Mail::to($user->email)->send(new ResetPasswordMail($user, $code));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'envoi de l\'e-mail de validation.');
        }
        return redirect()->route('reset.password.form');

    }

    public function showVerifyForm()
    {
        return view('auth.verify-code');
    }

    public function verifyCode(Request $request)
    {
        $messages = [

            'code.required' => 'Nous avons envoyé un code à votre adresse e-mail. Veuillez le saisir pour continuer.',
            'code.in' => 'Le code que vous avez saisi est incorrect. Veuillez réessayer.',
        ];
        $request->validate([
            'code' => 'required|string|in:' . session('reset_code'),
        ],$messages);

        // Supprimer le code après vérification
        session(['reset_verified' => true]);

        return redirect()->route('password.reset.form');
    }

    public function showResetForm()
    {
        if (!session('reset_verified')) {
            return redirect()->route('reset.password.form')->withErrors('Accès non autorisé.');
        }

        return view('auth.reset-password');
    }

    public function changePassword(Request $request)
    {
        $messages = [

            'password.required' => 'Veuillez saisir un nouveau mot de passe.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ];

        $request->validate([
            'password' => 'required|min:8|confirmed',
        ],$messages);

        if (!session()->has('reset_email')) {
            return redirect()->route('forgot.password')
                ->with('error', 'Session de réinitialisation expirée. Veuillez recommencer le processus.');
        }


        $user = User::where('email', session('reset_email'))->first();


        if (!$user) {
            return redirect()->route('forgot.password')
                ->with('error', 'Utilisateur non trouvé. Veuillez recommencer le processus.');
        }


        $user->update([
            'password' => bcrypt($request->password),
            'code_reset_password' => null
        ]);


        \Log::info('Mot de passe modifié pour l\'utilisateur: ' . $user->email);


        session()->forget(['reset_email', 'reset_code', 'reset_verified']);

        return redirect()->route('login')
            ->with('success', 'Votre mot de passe a été réinitialisé avec succès.');
    }
}
