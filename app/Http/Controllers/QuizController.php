<?php

// app/Http/Controllers/QuizController.php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;

use App\Models\Cours;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::all();
        return view('admin.apps.quiz.quizzes', compact('quizzes'));
    }

    public function create()
    {
        $cours = Cours::all(); // Récupérer tous les cours
        return view('admin.apps.quiz.quizcreate', compact('cours'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'titre' => 'required|string',
            'description' => 'required|string',
            'date_limite' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_limite',
            'cours_id' => 'required|exists:cours,id', // Validation de la clé étrangère
            'score_minimum' => 'required|integer',
        ]);

        Quiz::create($request->all());

        return redirect()->route('quizzes')->with('success', 'Quiz ajouté avec succès');
    }

    public function show(Quiz $quiz)
    {
        return view('admin.apps.quiz.quizshow', compact('quiz'));
    }

    public function edit(Quiz $quiz)
    {
        $cours = Cours::all(); // Récupérer tous les cours
        return view('admin.apps.quiz.quizedit', compact('quiz', 'cours'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $request->validate([
            'titre' => 'required|string',
            'description' => 'required|string',
            'date_limite' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_limite',
            'cours_id' => 'required|exists:cours,id', // Validation de la clé étrangère
            'score_minimum' => 'required|integer',
        ]);

        $quiz->update($request->all());

        return redirect()->route('quizzes')->with('success', 'Quiz mis à jour avec succès');
    }

    public function destroy(Quiz $quiz)
    {
        $quiz->delete();
        return redirect()->route('quizzes')->with('delete', 'Quiz supprimé avec succès');
    }
}
