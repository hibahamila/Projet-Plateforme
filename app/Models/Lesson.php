<?php

namespace App\Models;

use App\Models\Chapter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'duration', 'chapter_id', 'link'];

    // Mutateur pour formater la durée en HH:MM:SS avant l'enregistrement
    public function setDurationAttribute($value)
    {
        // Si la durée est déjà au format HH:MM:SS
        if (substr_count($value, ':') === 2) {
            $this->attributes['duration'] = $value;
            return;
        }
        
        // Si la durée est au format HH:MM
        if (substr_count($value, ':') === 1) {
            $parts = explode(':', $value);
            if (count($parts) === 2) {
                $this->attributes['duration'] = sprintf("%s:%s:00", $parts[0], $parts[1]);
                return;
            }
        }
        
        // Si c'est juste un nombre (minutes)
        if (is_numeric($value)) {
            $hours = floor($value / 60);
            $minutes = $value % 60;
            $this->attributes['duration'] = sprintf("%02d:%02d:00", $hours, $minutes);
            return;
        }
        
        // Valeur par défaut
        $this->attributes['duration'] = "00:00:00";
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($lesson) {
            Log::info("Lesson {$lesson->id} sauvegardée avec durée: {$lesson->duration}");
            
            // Mettre à jour le chapitre parent sans déclencher d'événements en cascade
            if ($lesson->chapter_id) {
                Log::info("Mise à jour du chapitre parent {$lesson->chapter_id}");
                
                DB::transaction(function () use ($lesson) {
                    // Récupérer le chapitre directement depuis la BDD
                    $chapter = Chapter::find($lesson->chapter_id);
                    if ($chapter) {
                        // Mise à jour directe en base pour éviter la récursivité
                        $newDuration = $chapter->calculateTotalDuration();
                        DB::table('chapters')->where('id', $chapter->id)
                            ->update(['duration' => $newDuration]);
                        
                        Log::info("Chapitre {$chapter->id} mis à jour avec durée: {$newDuration}");

                        // Mise à jour du cours parent si nécessaire
                        if ($chapter->course_id) {
                            $course = Course::find($chapter->course_id);
                            if ($course) {
                                $courseDuration = $course->calculateTotalDuration();
                                DB::table('courses')->where('id', $course->id)
                                    ->update(['duration' => $courseDuration]);
                                
                                Log::info("Cours {$course->id} mis à jour avec durée: {$courseDuration}");

                                // Mise à jour de la formation parente si nécessaire
                                if ($course->training_id) {
                                    $training = Training::find($course->training_id);
                                    if ($training) {
                                        $trainingDuration = $training->calculateTotalDuration();
                                        DB::table('trainings')->where('id', $training->id)
                                            ->update(['duration' => $trainingDuration]);
                                        
                                        Log::info("Formation {$training->id} mise à jour avec durée: {$trainingDuration}");
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });

        static::deleted(function ($lesson) {
            if ($lesson->chapter_id) {
                $chapter = Chapter::find($lesson->chapter_id);
                if ($chapter) {
                    $newDuration = $chapter->calculateTotalDuration();
                    DB::table('chapters')->where('id', $chapter->id)
                        ->update(['duration' => $newDuration]);
                    
                    Log::info("Après suppression, chapitre {$chapter->id} mis à jour avec durée: {$newDuration}");
                }
            }
        });
    }
}