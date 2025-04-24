<?php
namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\File;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class LessonController extends Controller
{
    public function index()
    {
        $lessons = Lesson::all();
        foreach ($lessons as $lesson) {
            $lesson->links = json_decode($lesson->link);
        }
        $chapitres = Chapter::all();
        return view('admin.apps.lesson.lessons', compact('lessons', 'chapitres'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|date_format:H:i:s', // Modifié pour inclure les secondes
            'chapter_id' => 'required|exists:chapters,id',
            'uploaded_files' => 'required|json',
            'link' => 'required|string',
        ]);

        // Décodage des fichiers téléchargés
        $uploadedFiles = json_decode($request->input('uploaded_files'), true);

        // Création de la leçon
        $lesson = Lesson::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'duration' => $request->input('duration'),
            'link' => json_encode(array_filter(array_map('trim', explode("\n", $request->input('link'))))),
            'chapter_id' => $request->input('chapter_id'),
        ]);

        Log::info('Leçon créée avec ID: ' . $lesson->id);
        Log::info('Fichiers reçus: ', $uploadedFiles);

        // Traitement des fichiers
        if (!empty($uploadedFiles) && is_array($uploadedFiles)) {
            foreach ($uploadedFiles as $file) {
                try {
                    // Vérifier la structure du fichier
                    if (!isset($file['path'])) {
                        Log::error("Structure de fichier incorrecte: ", $file);
                        continue;
                    }
                    
                    // Récupérer les informations du fichier
                    $tempPath = $file['path'];
                    $originalName = $file['original_name'] ?? $file['name'] ?? basename($tempPath);
                    
                    // Normaliser le chemin temporaire
                    if (!str_starts_with($tempPath, 'temp/')) {
                        $tempPath = 'temp/' . basename($tempPath);
                    }
                    
                    // Vérifier que le fichier temporaire existe
                    if (!Storage::disk('public')->exists($tempPath)) {
                        Log::error("Fichier temporaire non trouvé: {$tempPath}");
                        continue;
                    }
                    
                    // Créer un nouveau chemin unique
                    $newPath = 'files/' . uniqid() . '_' . $originalName;
                    
                    // Déplacer le fichier
                    Storage::disk('public')->move($tempPath, $newPath);
                    
                    // Créer l'enregistrement dans la table files
                    $fileRecord = File::create([
                        'name' => $originalName,
                        'file_path' => $newPath,
                        'file_type' => pathinfo($originalName, PATHINFO_EXTENSION),
                        'file_size' => Storage::disk('public')->size($newPath),
                        'lesson_id' => $lesson->id
                    ]);
                    
                    Log::info("Fichier enregistré avec succès: ", $fileRecord->toArray());
                    
                } catch (\Exception $e) {
                    Log::error("Erreur lors du traitement du fichier: " . $e->getMessage());
                    continue;
                }
            }
        }

        return redirect()->route('lessons')->with('success', 'Leçon ajoutée avec succès.');
    }


    public function create(Request $request)
    {
        $chapitreId = $request->query('chapitre_id');
        $chapitres = Chapter::all();
        return view('admin.apps.lesson.lessoncreate', compact('chapitres', 'chapitreId'));
    }


    // Méthode pour afficher le formulaire d'édition
    public function edit(Lesson $lesson)
    {
        $chapitres = Chapter::all();

        // Récupérer les fichiers existants
        $existingFiles = $lesson->files->map(function($file, $index) {
            return [
                'id' => $file->id,
                'name' => $file->name,
                'path' => $file->file_path,
                'size' => $file->file_size,
                'url' => asset('storage/' . $file->file_path),
            ];
        })->toArray();

        return view('admin.apps.lesson.lessonedit', compact('lesson', 'chapitres', 'existingFiles'));
    }

    public function update(Request $request, Lesson $lesson)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|date_format:H:i:s', // Modifié pour inclure les secondes
            'chapter_id' => 'required|exists:chapters,id',
            'uploaded_files' => 'nullable|json',
            'deleted_files' => 'nullable|json',
            'link' => 'nullable|string',
        ]);

        $data = $request->all();

        // Gestion des fichiers supprimés
        if ($request->has('deleted_files')) {
            $deletedFiles = json_decode($request->input('deleted_files'), true);
            if (is_array($deletedFiles)) {
                foreach ($deletedFiles as $fileId) {
                    $file = File::find($fileId);
                    if ($file) {
                        // Supprimer le fichier physique
                        if (Storage::disk('public')->exists($file->file_path)) {
                            Storage::disk('public')->delete($file->file_path);
                        }
                        // Supprimer l'entrée en base de données
                        $file->delete();
                    }
                }
            }
        }

        // Gestion des nouveaux fichiers uploadés
        if ($request->has('uploaded_files')) {
            $uploadedFiles = json_decode($request->input('uploaded_files'), true);
            if (is_array($uploadedFiles)) {
                foreach ($uploadedFiles as $file) {
                    try {
                        // Vérifier la structure du fichier
                        if (!isset($file['path'])) {
                            Log::error("Structure de fichier incorrecte: ", $file);
                            continue;
                        }
                        
                        // Récupérer les informations du fichier
                        $tempPath = $file['path'];
                        $originalName = $file['original_name'] ?? $file['name'] ?? basename($tempPath);
                        
                        // Normaliser le chemin temporaire
                        if (!str_starts_with($tempPath, 'temp/')) {
                            $tempPath = 'temp/' . basename($tempPath);
                        }
                        
                        // Vérifier que le fichier temporaire existe
                        if (!Storage::disk('public')->exists($tempPath)) {
                            Log::error("Fichier temporaire non trouvé: {$tempPath}");
                            continue;
                        }
                        
                        // Créer un nouveau chemin unique
                        $newPath = 'files/' . uniqid() . '_' . $originalName;
                        
                        // Déplacer le fichier
                        Storage::disk('public')->move($tempPath, $newPath);
                        
                        // Créer l'enregistrement dans la table files
                        $fileRecord = File::create([
                            'name' => $originalName,
                            'file_path' => $newPath,
                            'file_type' => pathinfo($originalName, PATHINFO_EXTENSION),
                            'file_size' => Storage::disk('public')->size($newPath),
                            'lesson_id' => $lesson->id
                        ]);
                        
                        Log::info("Fichier enregistré avec succès lors de la mise à jour: ", $fileRecord->toArray());
                        
                    } catch (\Exception $e) {
                        Log::error("Erreur lors du traitement du fichier dans update: " . $e->getMessage());
                        continue;
                    }
                }
            }
        }

        // Gestion des liens
        if ($request->has('link')) {
            if (!empty(trim($request->input('link')))) {
                $links = array_map(function($link) {
                    $trimmed = trim($link);
                    if (!empty($trimmed) && !preg_match('/^https?:\/\//i', $trimmed)) {
                        return 'http://' . $trimmed;
                    }
                    return $trimmed;
                }, explode("\n", $request->input('link')));
                $data['link'] = json_encode(array_filter($links));
            } else {
                $data['link'] = null;
            }
        } else {
            unset($data['link']);
        }

        unset($data['file_path']);
        unset($data['uploaded_files']);
        unset($data['deleted_files']);

        $lesson->update($data);

        return redirect()->route('lessons')->with('success', 'Leçon mise à jour avec succès.');
    }


    public function deleteFile($id)
    {
        $file = File::find($id);
        
        if ($file) {
            // Suppression du fichier physique
            if (Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }
            
            // Suppression de l'entrée en base de données
            $file->delete();
            
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false, 'message' => 'Fichier non trouvé'], 404);
    }

    // Méthode pour prévisualiser un document DOCX
    public function previewDocx(Request $request)
    {
        $fileUrl = $request->fileUrl;
        
        try {
            // Pour convertir DOCX en HTML, vous pouvez utiliser une bibliothèque comme 
            // PhpWord ou simplement extraire le contenu XML et le formater
            // Ici, on utilise une approche simplifiée
            
            $tempFile = tempnam(sys_get_temp_dir(), 'docx_');
            file_put_contents($tempFile, file_get_contents($fileUrl));
            
            $content = $this->extractDocxContent($tempFile);
            unlink($tempFile);
            
            return response()->json([
                'success' => true,
                'content' => $content
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    // Méthode pour extraire le contenu d'un document DOCX
    protected function extractDocxContent($file)
    {
        $zip = new ZipArchive();
        if ($zip->open($file) === true) {
            // Le contenu du document est généralement dans word/document.xml
            if (($index = $zip->locateName('word/document.xml')) !== false) {
                $content = $zip->getFromIndex($index);
                $zip->close();
                
                // Convertir XML en contenu HTML simple
                $xml = new \SimpleXMLElement($content);
                $namespace = $xml->getNamespaces(true);
                $xml->registerXPathNamespace('w', $namespace['w']);
                
                $paragraphs = $xml->xpath('//w:p');
                $html = '';
                
                foreach ($paragraphs as $p) {
                    $text = '';
                    $textNodes = $p->xpath('.//w:t');
                    
                    foreach ($textNodes as $textNode) {
                        $text .= (string)$textNode;
                    }
                    
                    if (!empty($text)) {
                        $html .= "<p>" . htmlspecialchars($text) . "</p>";
                    }
                }
                
                return $html;
            }
            $zip->close();
        }
        
        return '<p>Impossible d\'extraire le contenu du document.</p>';
    }
    
    // Méthode pour prévisualiser un fichier ZIP
    public function previewZip(Request $request)
    {
        $fileUrl = $request->fileUrl;
        
        try {
            $tempFile = tempnam(sys_get_temp_dir(), 'zip_');
            file_put_contents($tempFile, file_get_contents($fileUrl));
            
            $zip = new ZipArchive();
            $files = [];
            
            if ($zip->open($tempFile) === true) {
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $stat = $zip->statIndex($i);
                    $files[] = [
                        'name' => $stat['name'],
                        'size' => $stat['size']
                    ];
                }
                $zip->close();
            }
            
            unlink($tempFile);
            
            return response()->json([
                'success' => true,
                'files' => $files
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Méthode pour supprimer une leçon
    public function destroy($id)
    {
        try {
            $lesson = Lesson::findOrFail($id);
            $lessonName = $lesson->title;
            
            // Supprimer tous les fichiers associés
            foreach ($lesson->files as $file) {
                if (Storage::disk('public')->exists($file->file_path)) {
                    Storage::disk('public')->delete($file->file_path);
                }
            }
            
            $lesson->delete(); // Les fichiers sont également supprimés grâce à la relation onDelete('cascade')
            return response()->json(['successMessage' => "La leçon '{$lessonName}' a été supprimée avec succès!"]);
        } catch (\Exception $e) {
            return response()->json(['errorMessage' => 'Une erreur est survenue.']);
        }
    }


    public function uploadTemp(Request $request)
    {
        $request->validate([
            'file' => 'required|file'
        ]);

        $file = $request->file('file');
        
        // Récupérer le nom original du fichier avec son extension
        $originalName = $file->getClientOriginalName();
        
        // Générer un nom de fichier unique pour le stockage temporaire
        $uniqueName = uniqid() . '_' . $originalName;
        $path = $file->storeAs('temp', $uniqueName, 'public');

        return response()->json([
            'id' => uniqid(),
            'filepath' => $path,
            'original_name' => $originalName, // Retourner le nom original
            'message' => 'File uploaded successfully'
        ]);
    }

    // Méthode pour supprimer un fichier temporaire
    public function deleteTemp(Request $request)
    {
        Storage::disk('public')->delete($request->filepath);
        return response()->json(['success' => true]);
    }

    // Méthode pour obtenir un fichier
    public function getFile(Request $request)
    {
        $filePath = $request->query('filepath');
        
        // Vérifiez si le chemin du fichier est sécurisé
        if (strpos($filePath, '..') !== false) {
            return response()->json(['error' => 'Invalid file path'], 400);
        }
        
        $fullPath = storage_path('app/public/' . $filePath);

        if (!file_exists($fullPath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        // Obtenir l'extension du fichier
        $extension = pathinfo($fullPath, PATHINFO_EXTENSION);
        $mimeType = null;

        // Définir le type MIME en fonction de l'extension
        switch (strtolower($extension)) {
            case 'pdf':
                $mimeType = 'application/pdf';
                break;
            case 'doc':
                $mimeType = 'application/msword';
                break;
            case 'docx':
                $mimeType = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
                break;
            case 'xls':
                $mimeType = 'application/vnd.ms-excel';
                break;
            case 'xlsx':
                $mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                break;
            case 'ppt':
                $mimeType = 'application/vnd.ms-powerpoint';
                break;
            case 'pptx':
                $mimeType = 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
                break;
        }

        // Retourner le fichier avec le type MIME approprié s'il a été déterminé
        if ($mimeType) {
            return response()->file($fullPath, ['Content-Type' => $mimeType]);
        }

        // Pour les autres types, laisser le système déterminer le type MIME
        return response()->file($fullPath);
    }

    // Méthode pour uploader des fichiers
    public function uploadFiles(Request $request, $lessonId) {
        try {
            // Récupérer la leçon spécifiée
            $lesson = Lesson::find($lessonId);
            
            if (!$lesson) {
                return response()->json(['success' => false, 'message' => 'Leçon non trouvée.'], 404);
            }
            
            // Valider les fichiers téléchargés
            $request->validate([
                'files.*' => 'required|file', // Permet plusieurs fichiers
            ]);
            
            $uploadedFiles = [];
            
            // Traiter chaque fichier téléchargé
            foreach ($request->file('files') as $uploadedFile) {
                // Stocker le fichier et obtenir le chemin
                $path = $uploadedFile->store('files', 'public');
                
                // Créer un enregistrement dans la table files
                $file = File::create([
                    'name' => $uploadedFile->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $uploadedFile->getClientOriginalExtension(),
                    'file_size' => $uploadedFile->getSize(),
                    'lesson_id' => $lesson->id
                ]);
                
                $uploadedFiles[] = [
                    'id' => $file->id,
                    'name' => $file->name,
                    'path' => $file->file_path,
                    'url' => asset('storage/' . $file->file_path),
                    'size' => $file->file_size
                ];
            }
            
            return response()->json([
                'success' => true, 
                'files' => $uploadedFiles
            ]);
        } catch (\Exception $e) {
            // Log l'erreur pour le débogage
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}