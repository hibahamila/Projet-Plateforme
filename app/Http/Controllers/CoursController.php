<?php

namespace App\Http\Controllers;


use App\Models\Course;
use App\Models\Training;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CoursController extends Controller
{
    public function index()
    {
        $cours = Course::with('Training')->get();
        return view('admin.apps.cours.cours', compact('cours'));
    }

    public function create(Request $request)
    {
        $formation_id = $request->query('training_id'); 
        $formations = Training::all();
        
        return view('admin.apps.cours.courscreate', compact('formations', 'formation_id'));
    }
    
   
    public function store(Request $request)

    {
        if ($request->has('start_date')) {
            $request->merge(['start_date' => Carbon::createFromFormat('d/m/Y', $request->start_date)->format('Y-m-d')]);
        }
        
        if ($request->has('end_date')) {
            $request->merge(['end_date' => Carbon::createFromFormat('d/m/Y', $request->end_date)->format('Y-m-d')]);
        }
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date',
            'training_id' => 'required|exists:trainings,id',
        ]);
    
        // Création du cours
        $cours = Course::create($validatedData);
    
        // Flasher l'id du cours et le from_url dans la session pour l'alerte
        session()->flash('cours_id', $cours->id);
        session()->flash('from_url', $request->has('from_url') ? $request->from_url : false);
        
        // Si le training_id vient d'une URL (pas d'une sélection), le conserver
        if ($request->has('from_url') && $request->from_url === 'true') {
            return redirect()->route('courscreate', ['training_id' => $request->training_id, 'from_url' => 'true'])->withInput();
        }
        
        // Sinon, rediriger normalement
        return redirect()->route('courscreate')->withInput();
    }

    public function edit($id)
    {
        $cours = Course::findOrFail($id);
        $formations = Training::all();
        return view('admin.apps.cours.coursedit', compact('cours', 'formations'));
    }


    
  
    public function show($id)
    {
        $cours = Course::with('Training')->findOrFail($id);
        return view('admin.apps.cours.coursshow', compact('cours'));
    }
    public function update(Request $request, $id)
    {
        if ($request->has('start_date')) {
            $startDate = \Carbon\Carbon::createFromFormat('d/m/Y', $request->start_date)->format('Y-m-d');
            $request->merge(['start_date' => $startDate]);
        }
        
        if ($request->has('end_date')) {
            $endDate = \Carbon\Carbon::createFromFormat('d/m/Y', $request->end_date)->format('Y-m-d');
            $request->merge(['end_date' => $endDate]);
        }
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date',
            'training_id' => 'required|exists:trainings,id',
        ]);

        $cours = Course::findOrFail($id);
        
        // Mise à jour des champs validés - la durée sera automatiquement calculée
        // dans le boot method du modèle lors de la sauvegarde
        $cours->update($request->only([
            'title', 'description', 'start_date', 'end_date', 'training_id'
        ]));

        return redirect()->route('cours')->with('success', 'Cours mis à jour avec succès.');
    }
   
    public function destroy($id)
    {
        $cours = Course::findOrFail($id);
        $cours->delete();

        return redirect()->route('cours')->with('delete', 'Cours supprimé avec succès.');
    }
}