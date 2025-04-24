<?php

namespace App\Models;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Chapter extends Model 
{
    use HasFactory;
    
    protected $fillable = ['title', 'description', 'duration', 'course_id'];
    
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
    
    // Convertit les secondes totales en format HH:MM:SS
    public function secondsToTime($totalSeconds)
    {
        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $seconds = $totalSeconds % 60;
        
        return sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
    }
    
    // Convertit le format HH:MM:SS en secondes totales
    public function timeToSeconds($time)
    {
        // Si c'est une closure ou callable, retourner 0
        if (is_callable($time) && !is_string($time)) {
            Log::warning("Tentative de conversion d'une closure en secondes");
            return 0;
        }
        
        // Si null ou vide
        if (empty($time) || $time === "00:00:00") {
            return 0;
        }
        
        // Si ce n'est pas une chaîne
        if (!is_string($time)) {
            Log::warning("Type de durée invalide: ".gettype($time));
            return 0;
        }
        
        // Vérification du format
        if (strpos($time, ':') === false) {
            // Si c'est juste un nombre, supposer que ce sont des minutes
            if (is_numeric($time)) {
                return (int)$time * 60;
            }
            Log::warning("Format de durée invalide: ".$time);
            return 0;
        }
        
        // Séparation heures:minutes:secondes ou minutes:secondes
        $parts = explode(':', $time);
        
        if (count($parts) === 3) {
            // Format HH:MM:SS
            return (int)$parts[0] * 3600 + (int)$parts[1] * 60 + (int)$parts[2];
        } else if (count($parts) === 2) {
            // Format MM:SS (pour rétrocompatibilité)
            return (int)$parts[0] * 60 + (int)$parts[1];
        }
        
        Log::warning("Format de durée non reconnu: ".$time);
        return 0;
    }
    
    // Calcul automatique de la durée totale du chapitre basée sur les leçons
    public function calculateTotalDuration() 
    {
        // Si nouveau chapitre non sauvegardé
        if (!$this->exists) {
            return "00:00:00";
        }
        
        try {
            // Récupérer les leçons à partir de la base de données pour éviter les problèmes de cache
            $lessons = Lesson::where('chapter_id', $this->id)->get();
            
            $totalSeconds = 0;
            foreach ($lessons as $lesson) {
                if ($lesson->duration) {
                    $totalSeconds += $this->timeToSeconds($lesson->duration);
                }
            }
            
            return $this->secondsToTime($totalSeconds);
        } catch (\Exception $e) {
            Log::error("Erreur calcul durée chapitre: ".$e->getMessage());
            return "00:00:00";
        }
    }
    
    // Accesseur pour obtenir la durée actuelle (pour affichage)
    public function getCurrentDurationAttribute()
    {
        return $this->calculateTotalDuration();
    }
    
    protected static function boot() 
    {
        parent::boot();
        
        static::saving(function ($chapter) {
            // Ne pas recalculer si déjà en cours de mise à jour
            if (!$chapter->isDirty('duration')) {
                $chapter->duration = $chapter->calculateTotalDuration();
                Log::info("Chapter {$chapter->id}: durée recalculée à {$chapter->duration}");
            }
        });
    }
    
    // Méthode pour forcer la mise à jour de la durée
    public function updateDuration()
    {
        $newDuration = $this->calculateTotalDuration();
        Log::info("Mise à jour directe de la durée du chapitre {$this->id} à {$newDuration}");
        DB::table('chapters')->where('id', $this->id)->update(['duration' => $newDuration]);
    }
}