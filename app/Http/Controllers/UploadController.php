<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function uploadTemp(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $fileId = time() . '_' . Str::random(10);
            $filename = $fileId . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('temp', $filename, 'public');
            
            return response()->json([
                'success' => true, 
                'filepath' => $path,
                'id' => $fileId,
                'name' => $originalName
            ]);
        }
        
        return response()->json(['success' => false]);
    }
    
    public function deleteTemp(Request $request)
    {
        $fileId = $request->input('id');
        
        // Rechercher tous les fichiers dans le dossier temp qui commencent par l'ID du fichier
        $files = Storage::disk('public')->files('temp');
        
        foreach ($files as $file) {
            if (strpos(basename($file), $fileId) === 0) {
                Storage::disk('public')->delete($file);
                return response()->json(['success' => true]);
            }
        }
        
        return response()->json(['success' => false, 'message' => 'File not found']);
    }
}