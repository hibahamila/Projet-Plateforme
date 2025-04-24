<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reponse;
use App\Models\Question;

class ReponseController extends Controller
{
    // Afficher toutes les réponses
    public function index()
    {
        $reponses = Reponse::with('question')->get();
        return view('admin.apps.reponse.reponses', compact('reponses'));
    }

    // Afficher le formulaire de création
    public function create()
    {
        $questions = Question::all();
        return view('admin.apps.reponse.reponsecreate', compact('questions'));
    }

    // Enregistrer une nouvelle réponse
    public function store(Request $request)
    {
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'contenu' => 'required|string|max:255',
            'est_correcte' => 'required|boolean',
        ]);

        Reponse::create([
            'question_id' => $request->question_id,
            'contenu' => $request->contenu,
            'est_correcte' => $request->est_correcte,
        ]);

        return redirect()->route('reponses')->with('success', 'Réponse ajoutée avec succès.');
    }

    // Afficher une seule réponse
    public function show(Reponse $reponse)
    {
        return view('admin.apps.reponse.reponseshow', compact('reponse'));
    }

    // Afficher le formulaire de modification
    public function edit(Reponse $reponse)
    {
        $questions = Question::all();
        return view('admin.apps.reponse.reponseedit', compact('reponse', 'questions'));
    }

    // Mettre à jour une réponse
    public function update(Request $request, Reponse $reponse)
    {
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'contenu' => 'required|string|max:255',
            'est_correcte' => 'required|boolean',
        ]);

        $reponse->update($request->all());

        return redirect()->route('reponses')->with('success', 'Réponse mise à jour avec succès.');
    }

    // Supprimer une réponse
    public function destroy(Reponse $reponse)
    {
        $reponse->delete();
        return redirect()->route('reponses')->with('delete', 'Réponse supprimée avec succès.');
    }
}
