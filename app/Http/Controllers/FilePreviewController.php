<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class FilePreviewController extends Controller
{
    /**
     * Prévisualise un fichier
     *
     * @param Request $request La requête HTTP
     * @return \Illuminate\Http\Response
     */
    public function preview(Request $request)
    {
        $filePath = $request->input('path');
        
        if (!Storage::exists($filePath)) {
            return response()->json(['error' => 'Fichier non trouvé'], 404);
        }
        
        $fileContents = Storage::get($filePath);
        $mimeType = Storage::mimeType($filePath);
        
        return Response::make($fileContents, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"',
        ]);
    }
    
    /**
     * Télécharger un fichier temporaire
     *
     * @param Request $request La requête HTTP
     * @return \Illuminate\Http\Response
     */
    public function uploadTemp(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $originalName = $request->input('original_name', $file->getClientOriginalName());
            
            // Stocker dans un dossier temporaire
            $path = $file->store('temp/uploads');
            
            return response()->json([
                'success' => true,
                'filepath' => $path,
                'original_name' => $originalName
            ]);
        }
        
        return response()->json(['error' => 'Aucun fichier reçu'], 400);
    }
    
    /**
     * Supprimer un fichier temporaire
     *
     * @param Request $request La requête HTTP
     * @return \Illuminate\Http\Response
     */
    public function deleteTemp(Request $request)
    {
        $filepath = $request->input('filepath');
        
        if (Storage::exists($filepath)) {
            Storage::delete($filepath);
            return response()->json(['success' => true]);
        }
        
        return response()->json(['error' => 'Fichier non trouvé'], 404);
    }
}