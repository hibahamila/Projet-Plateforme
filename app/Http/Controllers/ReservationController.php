<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReservationController extends Controller
{
    /**
     * Créer une nouvelle réservation
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
{
    // Vérifier si l'utilisateur est connecté
    if (!Auth::check()) {
        return response()->json([
            'success' => false,
            'message' => 'Vous devez être connecté pour effectuer une réservation'
        ], 401);
    }

    // Récupérer le panier de l'utilisateur
    $cart = Cart::where('user_id', Auth::id())->first();

    // Vérifier si le panier existe
    if (!$cart) {
        return response()->json([
            'success' => false,
            'message' => 'Aucun panier trouvé'
        ], 404);
    }

    // Vérifier si le panier a des formations
    // Attention : training_ids est déjà un tableau grâce au cast, pas besoin de json_decode
    if (empty($cart->training_ids)) {
        return response()->json([
            'success' => false,
            'message' => 'Votre panier est vide'
        ], 400);
    }

    try {
        // Créer la réservation
        $reservation = new Reservation();
        $reservation->cart_id = $cart->id;
        $reservation->user_id = Auth::id();
        $reservation->reservation_date = $request->input('reservation_date', Carbon::now()->toDateString());
        $reservation->reservation_time = $request->input('reservation_time', Carbon::now()->toTimeString());
        $reservation->status = false; // Non payé par défaut
        $reservation->save();

        // Créer un nouveau panier vide pour l'utilisateur
        // $newCart = new Cart();
        // $newCart->user_id = Auth::id();
        // $newCart->training_ids = []; // Déjà un tableau, Laravel le convertira en JSON
        // $newCart->save();

        return response()->json([
            'success' => true,
            'message' => 'Réservation effectuée avec succès',
            'reservation_id' => $reservation->id,
            'clearCart' => true
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la création de la réservation: ' . $e->getMessage()
        ], 500);
    }
}
    /**
     * Vérifier si l'utilisateur est authentifié
     *
     * @return \Illuminate\Http\Response
     */
    public function checkAuth()
    {
        return response()->json([
            'authenticated' => Auth::check()
        ]);
    }

    public function getDetails()
{
    $user = Auth::user();
    $cart = Cart::where('user_id', $user->id)->first();
    
    if (!$cart) {
        return response()->json(['trainings' => [], 'discount' => 0]);
    }
    
    $trainings = $cart->getFormations();
    $discount = 0; // À remplacer par votre logique de remise
    
    return response()->json([
        'trainings' => $trainings->map(function($training) {
            return [
                'id' => $training->id,
                'title' => $training->title,
                'price' => $training->price
            ];
        }),
        'discount' => $discount
    ]);
}

public function checkReservation()
{
    // Vérifier si l'utilisateur est connecté
    if (!Auth::check()) {
        return response()->json([
            'hasReservation' => false
        ]);
    }

    // Rechercher la dernière réservation active de l'utilisateur
    $reservation = Reservation::where('user_id', Auth::id())
        ->where('status', false)  // Non payé / actif
        ->latest()
        ->first();

    return response()->json([
        'hasReservation' => $reservation ? true : false,
        'reservation_id' => $reservation ? $reservation->id : null
    ]);
}

/**
 * Annule une réservation existante
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\Response
 */
public function cancelReservation(Request $request)
{
    // Vérifier si l'utilisateur est connecté
    if (!Auth::check()) {
        return response()->json([
            'success' => false,
            'message' => 'Vous devez être connecté pour annuler une réservation'
        ], 401);
    }

    $reservationId = $request->input('reservation_id');
    
    // Rechercher la réservation de l'utilisateur
    $reservation = Reservation::where('id', $reservationId)
        ->where('user_id', Auth::id())
        ->first();

    if (!$reservation) {
        return response()->json([
            'success' => false,
            'message' => 'Réservation non trouvée'
        ], 404);
    }

    try {
        // Supprimer la réservation
        $reservation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Réservation annulée avec succès'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de l\'annulation de la réservation: ' . $e->getMessage()
        ], 500);
    }
}
}