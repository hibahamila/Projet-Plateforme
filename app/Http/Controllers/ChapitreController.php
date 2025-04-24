<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;

use App\Models\Chapter;
use App\Models\Course;
use Illuminate\Http\Request;

class ChapitreController extends Controller
{
    public function index()
    {
        $chapitres = Chapter::with('Course')->get();
        return view('admin.apps.chapitre.chapitres', compact('chapitres'));
    }

    public function create(Request $request)
    {
        $cours_id = $request->query('cours_id');
        $cours = Course::all();
        return view('admin.apps.chapitre.chapitrecreate', compact('cours', 'cours_id'));
    }


public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'course_id' => 'required|exists:Courses,id',
    ]);

    // Créer le chapitre
    $chapitre = Chapter::create([
        'title' => $request->title,
        'description' => $request->description,
        'course_id' => $request->course_id,
    ]);

    // Flasher l'id du chapitre ET du cours dans la session
    session()->flash('chapitre_id', $chapitre->id);
    session()->flash('cours_id', $request->course_id);
    
    // Stocker également l'information sur la source du cours (URL ou manuel)
    session()->flash('cours_source', $request->input('cours_source', 'manual'));
    
    // Rediriger vers la même page
    return redirect()->route('chapitrecreate')->withInput();
}
   
    public function edit($id)
    {
        $chapitre = Chapter::findOrFail($id);
        $cours = Course::all();
        return view('admin.apps.chapitre.chapitreedit', compact('chapitre', 'cours'));
    }

    public function show($id)
    {
        $chapitre = Chapter::with(['Course', 'lessons'])->findOrFail($id);
        // Ajout des leçons pour pouvoir afficher la durée calculée
        return view('admin.apps.chapitre.chapitreshow', compact('chapitre'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'course_id' => 'required|exists:courses,id',
        ]);

        // Suppression du champ duration de la validation car il sera calculé automatiquement
        $chapitre = Chapter::findOrFail($id);
        
        // Mise à jour des champs validés - la durée sera automatiquement calculée 
        // dans le boot method du modèle lors de la sauvegarde
        $chapitre->update([
            'title' => $request->title,
            'description' => $request->description,
            'course_id' => $request->course_id,
        ]);

        return redirect()->route('chapitres')->with('success', 'Chapitre mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $chapitre = Chapter::findOrFail($id);
        $chapitre->delete();

        return redirect()->route('chapitres')->with('delete', 'Chapitre supprimé avec succès.');
    }
}