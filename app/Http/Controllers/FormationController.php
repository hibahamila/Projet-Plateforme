<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Feedback;
use App\Models\Training;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class FormationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function show($id)
{
    $formation = Training::with(['user', 'category', 'feedbacks', 'courses'])
        ->findOrFail($id);

    $formation->total_feedbacks = $formation->feedbacks->count();
    $formation->average_rating = $formation->feedbacks->avg('rating_count');
    $formation->sum_ratings = $formation->feedbacks->sum('rating_count');

    // Si c'est une requête AJAX, renvoyer les données en JSON
    if (request()->ajax() || request()->wantsJson()) {
        return response()->json([
            'formation' => $formation
        ]);
    }

    // Sinon, afficher la vue normale
    return view('admin.apps.formation.formationshow', compact('formation'));
}
public function index(Request $request) {
    $categories = Category::withCount('trainings')->get();
    
    $query = Training::with(['user', 'category', 'feedbacks', 'courses']);
    
    // Filtrage par catégorie - UNIQUEMENT si une valeur est explicitement fournie
    if ($request->has('category_id') && $request->category_id !== null && $request->category_id !== '') {
        $query->where('category_id', $request->category_id);
    }
    
    // Filtrage par statut - UNIQUEMENT si une valeur est explicitement fournie et status_all n'est pas vrai
    if (!$request->has('status_all') && $request->has('status') && $request->status !== null && $request->status !== '') {
        $query->where('status', $request->status);
    }
    
    // Recherche par terme
    if ($request->filled('search')) {
        $searchTerm = $request->search;
        $query->where(function($q) use ($searchTerm) {
            $q->where('title', 'LIKE', "%{$searchTerm}%")
              ->orWhere('description', 'LIKE', "%{$searchTerm}%");
        });
    }
    
    $formations = $query->get();
    
    $formations->each(function ($formation) {
        $formation->final_price = $formation->discount > 0
            ? $formation->price * (1 - $formation->discount / 100)
            : $formation->price;
        
        $formation->total_feedbacks = $formation->feedbacks->count();
        $formation->average_rating = $formation->total_feedbacks > 0
            ? round($formation->feedbacks->sum('rating_count') / $formation->total_feedbacks, 1)
            : null;
        $formation->cours_count = $formation->courses->count();
    });
    
    $totalFeedbacks = $formations->sum('total_feedbacks');
    
    // Définir le titre en fonction des filtres
    if ($request->has('category_id') && $request->category_id !== null && $request->category_id !== '') {
        $title = Category::find($request->category_id)->title;
    } else {
        $title = 'Toutes les formations';
    }
    
    // Pour les requêtes AJAX
    if ($request->ajax() === true || $request->wantsJson() === true || $request->header('X-Requested-With') === 'XMLHttpRequest') {
        return response()->json([
            'formations' => $formations,
            'title' => $title,
            'totalFeedbacks' => $totalFeedbacks
        ]);
    }
    
    // Pour les requêtes normales
    return view('admin.apps.formation.formations', compact('formations', 'categories', 'title', 'totalFeedbacks'));
}

public function create()
{
    $professeurs = User::whereHas('roles', function($query) {
        $query->where('name', 'professeur');
    })
    ->where('status', 'active') // Ajouter cette condition pour filtrer par statut actif
    ->get(['id', 'name', 'lastname']);

    $categories = Category::all();

    return view('admin.apps.formation.formationcreate', compact('professeurs', 'categories'));
}

public function edit($id)
{
    $formation = Training::findOrFail($id);

    $professeurs = User::whereHas('roles', function($query) {
        $query->where('name', 'professeur');
    })
    ->where('status', 'active') // Ajouter cette condition pour filtrer par statut actif
    ->get(['id', 'name', 'lastname']);

    $categories = Category::all();

    return view('admin.apps.formation.formationedit', compact('formation', 'professeurs', 'categories'));
}

    public function store(Request $request)
{
    // Convertir les dates du format DD/MM/YYYY au format YYYY-MM-DD
    if ($request->has('start_date')) {
        $request->merge(['start_date' => Carbon::createFromFormat('d/m/Y', $request->start_date)->format('Y-m-d')]);
    }
    
    if ($request->has('end_date')) {
        $request->merge(['end_date' => Carbon::createFromFormat('d/m/Y', $request->end_date)->format('Y-m-d')]);
    }
    try {
        // Définir les règles de validation
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:payante,gratuite',
            'category_id' => 'required|exists:categories,id',
            'user_id' => 'required|exists:users,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'publication_type' => 'required|in:now,later',
            'publish_date' => 'nullable|date|after_or_equal:now',
            'total_seats' => 'required|integer|min:1', // Ajout de la validation pour le nombre de places
        ];

        // Modification de la règle d'image pour prendre en compte l'option "keep_image"
        if ($request->has('keep_image') && $request->has('current_image')) {
            $rules['image'] = 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048';
        } else {
            $rules['image'] = 'required|image|mimes:jpg,jpeg,png,gif|max:2048';
        }

        // Ajout conditionnel de règles pour le prix
        if ($request->type === 'payante') {
            $rules['price'] = 'required|numeric|min:0';
        }

        // Valider les données
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        // Gestion de l'image
        if ($request->hasFile('image')) {
            // Assurez-vous que le répertoire existe
            if (!Storage::disk('public')->exists('formations')) {
                Storage::disk('public')->makeDirectory('formations');
            }

            $file = $request->file('image');
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $imagePath = $file->storeAs('formations', $fileName, 'public');

            // Vérifier si l'image a été correctement enregistrée
            if (!Storage::disk('public')->exists($imagePath)) {
                throw new \Exception('Échec de l\'enregistrement de l\'image');
            }
        } elseif ($request->has('keep_image') && $request->has('current_image')) {
            // Utiliser l'image existante
            $imagePath = $request->current_image;
        } else {
            throw new \Exception('Image requise');
        }

        // Préparation des données pour la formation
        $formationData = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'category_id' => $validated['category_id'],
            'user_id' => $validated['user_id'],
            'image' => $imagePath,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'is_bestseller' => $request->has('is_bestseller') ? 1 : 0,
            'total_seats' => $validated['total_seats'], // Ajout du nombre de places
        ];

        // Gestion du prix selon le type
        $formationData['price'] = ($validated['type'] === 'payante') ? $validated['price'] : 0;
        $formationData['discount'] = $request->has('discount') ? $request->discount : 0;
        $formationData['final_price'] = ($validated['type'] === 'payante')
            ? ($formationData['price'] * (1 - $formationData['discount'] / 100))
            : 0;

        // Gestion de la publication
        if ($validated['publication_type'] === 'later' && isset($validated['publish_date'])) {
            $formationData['publish_date'] = Carbon::parse($validated['publish_date'])
                ->format('Y-m-d H:i:s');
            $formationData['status'] = 0; // Non publiée
        } else {
            $formationData['status'] = 1; // Publiée immédiatement
            $formationData['publish_date'] = null;
        }

        // Log pour débogage
        Log::info('Données formation avant création', $formationData);

        DB::beginTransaction();

        // Vérifiez que le modèle Training inclut ces champs dans $fillable
        $formation = Training::create($formationData);

        if (!$formation || !$formation->exists) {
            throw new \Exception('La création de la formation a échoué');
        }

        DB::commit();

        Log::info('Formation créée avec succès', [
            'formation_id' => $formation->id,
            'title' => $formation->title,
            'user_id' => Auth::id()
        ]);
        session()->flash('current_formation_id', $formation->id);
        session()->flash('from_formation', true);
        
        // Flasher les données pour SweetAlert2 et pour conserver les données du formulaire
        return back()->withInput()
            ->with('success', 'Formation créée avec succès')
            ->with('formation_id', $formation->id)
            ->with('old_data', $formationData);
 
    } catch (\Exception $e) {
        // En cas d'erreur, annuler la transaction
        if (isset($formation) && DB::transactionLevel() > 0) {
            DB::rollBack();
        }

        Log::error('Erreur lors de la création de la formation', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'user_id' => Auth::id(),
            'request_data' => $request->all()
        ]);

        // Supprimer l'image si elle a été uploadée en cas d'échec
        if (isset($imagePath) && $imagePath && !$request->has('current_image')) {
            Storage::disk('public')->delete($imagePath);
        }

        return back()->withErrors('Erreur lors de la création de la formation: ' . $e->getMessage())->withInput();
    }
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
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'type' => 'required|in:payante,gratuite',
        'price' => $request->type === 'payante' ? 'required|numeric|min:0' : 'nullable',
        'discount' => $request->type === 'payante' ? 'nullable|numeric|min:0|max:100' : 'nullable',
        'final_price' => $request->type === 'payante' ? 'required|numeric|min:0' : 'nullable',
        'category_id' => 'required|exists:categories,id',
        'user_id' => 'required|exists:users,id',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'delete_image' => 'nullable|boolean',
        'publication_type' => 'required|in:now,later',
        'publish_date' => 'required_if:publication_type,later|nullable|date',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'total_seats' => 'required|integer|min:1', // Ajout de la validation pour le nombre de places
    ]);

    try {
        DB::beginTransaction();

        $formation = Training::findOrFail($id);

        // Gestion de l'image
        if ($request->hasFile('image')) {
            // Si une nouvelle image est téléchargée, supprimer l'ancienne
            if ($formation->image) {
                Storage::disk('public')->delete($formation->image);
            }
            // Stocker la nouvelle image
            $imagePath = $request->file('image')->store('formations', 'public');
            $formation->image = $imagePath;
        } elseif (isset($request->delete_image) && $request->delete_image == 1 && $formation->image) {
            // Si on demande de supprimer l'image et qu'une image existe
            Storage::disk('public')->delete($formation->image);
            // Image par défaut
            $formation->image = 'formations/default.jpg';
        }

        // Mise à jour des champs
        $formation->title = $validated['title'];
        $formation->description = $validated['description'];
        $formation->start_date = $validated['start_date'];
        $formation->end_date = $validated['end_date'];
        $formation->type = $validated['type'];
        $formation->category_id = $validated['category_id'];
        $formation->user_id = $validated['user_id'];
        $formation->total_seats = $validated['total_seats']; // Ajout du nombre de places

        // Gestion des prix pour les formations payantes
        if ($validated['type'] === 'payante') {
            $formation->price = $validated['price'];
            $formation->discount = $validated['discount'] ?? 0;
            $formation->final_price = $validated['final_price'];
        } else {
            $formation->price = 0;
            $formation->discount = 0;
            $formation->final_price = 0;
        }

        // Gestion de la publication
        if ($validated['publication_type'] === 'now') {
            $formation->status = true;
            $formation->publish_date = null;
        } else {
            $formation->status = false;
            $formation->publish_date = $validated['publish_date'];
        }

        $formation->save();
        DB::commit();

        return redirect()->route('formations')->with('success', 'Formation mise à jour avec succès');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Erreur lors de la mise à jour de la formation', [
            'id' => $id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return back()->withErrors('Erreur lors de la mise à jour: ' . $e->getMessage())->withInput();
    }
}

    public function destroy($id)
    {
        $formation = Training::findOrFail($id);

        try {
            DB::beginTransaction();

            if ($formation->image) {
                Storage::disk('public')->delete($formation->image);
            }

            $formation->delete();
            DB::commit();

            Log::info('Formation supprimée', [
                'id' => $id,
                'user_id' => Auth::id(),
                'title' => $formation->title,
            ]);

            return redirect()->route('formations')->with('success', 'Formation supprimée avec succès');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erreur lors de la suppression de la formation', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Erreur lors de la suppression de la formation');
        }
    }

    
}
