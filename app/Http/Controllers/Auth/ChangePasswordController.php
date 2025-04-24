<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ChangePasswordController extends Controller
{
    /**
     * Afficher le formulaire de modification de mot de passe.
     *
     * @return \Illuminate\View\View
     */
    public function showChangePasswordForm()
    {
        return view('auth.change-password');
    }


    /**
     * Traiter la modification de mot de passe.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function skipPasswordChange(Request $request)
    {
        $user = Auth::user();
        $user->first_login = false;
        $user->save();

        return redirect()->route('dashboard.index')
            ->with('success', 'Vous pouvez modifier votre mot de passe plus tard.');
    }
    public function changePassword(Request $request)
    {

        $user = Auth::user();

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {

            $user->password = Hash::make($request->password);
            $user->first_login = false; // Marquer que ce n'est plus la première connexion
            $user->save();

            


            return redirect()->route('dashboard.index')
                ->with('success', 'Votre mot de passe a été modifié avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la modification du mot de passe: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors de la modification du mot de passe.');
        }
    }
}
