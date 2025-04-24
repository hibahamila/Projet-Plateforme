<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Reponse;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index()
    {
        // Récupérer toutes les questions avec leur quiz associé
        $questions = Question::with('quiz')->get();
        return view('admin.apps.question.questions', compact('questions'));
    }

    public function create()
    {
        // Récupérer tous les quiz pour le formulaire de création
        $quizzes = Quiz::all();
        return view('admin.apps.question.questioncreate', compact('quizzes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'enonce' => 'required|string|max:255',
            'quiz_id' => 'required|exists:quizzes,id', 
        ]);

        // Création de la question
        Question::create($request->all());

        return redirect()->route('questions')->with('success', 'Question ajoutée avec succès.');
    }

    public function show($id)
    {
        // Récupérer la question avec son quiz associé
        $question = Question::with('quiz')->findOrFail($id);
        return view('admin.apps.question.questionshow', compact('question'));
    }

    public function edit($id)
    {
        // Récupérer la question et tous les quiz pour le formulaire de modification
        $question = Question::findOrFail($id);
        $quizzes = Quiz::all();
        $reponse = Reponse::where('question_id', $id)->get(); // Assurez-vous que la variable $reponse est définie

        return view('admin.apps.question.questionedit', compact('question', 'quizzes', 'reponse'));
        // return view('admin.apps.question.questionedit', compact('question', 'quizzes'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'enonce' => 'required|string|max:255',
            'quiz_id' => 'required|exists:quizzes,id',
        ]);

        // Mise à jour de la question
        $question = Question::findOrFail($id);
        $question->update($request->all());

        return redirect()->route('questions')->with('success', 'Question mise à jour avec succès.');
    }

    public function destroy($id)
    {
        // Suppression de la question
        $question = Question::findOrFail($id);
        $question->delete();

        return redirect()->route('questions')->with('delete', 'Question supprimée avec succès.');
    }
}
