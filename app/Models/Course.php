<?php

namespace App\Models;

use App\Models\Chapter;
use App\Models\Lesson;

use App\Models\Training;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Course extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'duration',
        'start_date',
        'end_date',
        'training_id',
    ];
    protected $table = 'courses';


    // Relation avec Formation
    public function Training()
    {
        return $this->belongsTo(Training::class);
    }
    
    public function Chapters()
    {
        return $this->hasMany(Chapter::class);
    }

    // Convertit les secondes en format HH:MM:SS
    public function secondsToTime($totalSeconds)
    {
        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $seconds = $totalSeconds % 60;

        $result = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
        Log::info("Cours {$this->id}: Convertir {$totalSeconds} secondes en temps: {$result}");
        return $result;
    }

    // Convertit le format HH:MM:SS ou HH:MM en secondes
    public function timeToSeconds($time)
    {
        if (empty($time) || $time == "00:00:00" || $time == "00:00") {
            Log::info("Cours {$this->id}: timeToSeconds reçoit une durée invalide: " . ($time ?? 'null'));
            return 0;
        }

        $parts = explode(':', $time);
        
        if (count($parts) === 3) {
            // Format HH:MM:SS
            $result = (int)$parts[0] * 3600 + (int)$parts[1] * 60 + (int)$parts[2];
            Log::info("Cours {$this->id}: Convertir {$time} (HH:MM:SS) en secondes: {$result}");
            return $result;
        } else if (count($parts) === 2) {
            // Format HH:MM (rétrocompatibilité)
            $result = (int)$parts[0] * 3600 + (int)$parts[1] * 60;
            Log::info("Cours {$this->id}: Convertir {$time} (HH:MM) en secondes: {$result}");
            return $result;
        }
        
        Log::warning("Cours {$this->id}: Format de durée non reconnu: {$time}");
        return 0;
    }

    // Calcul de la durée totale du cours basée sur les chapitres
    public function calculateTotalDuration()
    {
        Log::info("Cours {$this->id}: Début du calcul de la durée totale");

        $totalSeconds = 0;

        // Récupère tous les chapitres associés à ce cours
        $allChapitres = $this->Chapters()->get();
        Log::info("Cours {$this->id}: Nombre de chapitres trouvés: " . $allChapitres->count());

        foreach ($allChapitres as $chapitre) {
            Log::info("Cours {$this->id}: Traitement du chapitre {$chapitre->id} avec durée: {$chapitre->duration}");
            $chapitreSeconds = $this->timeToSeconds($chapitre->duration);
            $totalSeconds += $chapitreSeconds;
            Log::info("Cours {$this->id}: Après ajout du chapitre {$chapitre->id}, total secondes: {$totalSeconds}");
        }

        $result = $this->secondsToTime($totalSeconds);
        Log::info("Cours {$this->id}: Durée totale calculée: {$result}");
        return $result;
    }

    // Désactivez le boot pour éviter les boucles - la mise à jour se fera via Lesson::boot()
    protected static function boot()
    {
        parent::boot();

        // On garde seulement le calcul au moment de la sauvegarde
        static::saving(function ($cours) {
            $cours->duration = $cours->calculateTotalDuration();
            Log::info("Cours {$cours->id}: Calcul de durée avant sauvegarde: {$cours->duration}");
        });
    }
}