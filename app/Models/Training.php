<?php

namespace App\Models;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Course;

use App\Models\Feedback;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Training extends Model
{
    use HasFactory;

    protected $table = 'trainings';

    protected $fillable = [
        'title',
        'description',
        'duration',
        'type',
        'status',
        'start_date',
        'end_date',
        'price',
        'discount',
        'final_price',
        'image',
        'publish_date',
        'category_id',
        'user_id',
        'total_seats',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class, 'training_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    // Convertit les secondes en format HH:MM:SS
    public function secondsToTime($totalSeconds)
    {
        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $seconds = $totalSeconds % 60;

        $result = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
        Log::info("Training {$this->id}: Convertir {$totalSeconds} secondes en temps: {$result}");
        return $result;
    }

    // Convertit le format HH:MM:SS ou HH:MM en secondes
    public function timeToSeconds($time)
    {
        if (empty($time) || $time == "00:00:00" || $time == "00:00" || strpos($time, ':') === false) {
            Log::info("Training {$this->id}: timeToSeconds reçoit une durée invalide: " . ($time ?? 'null'));
            return 0;
        }

        $parts = explode(':', $time);
        
        if (count($parts) === 3) {
            // Format HH:MM:SS
            $result = (int)$parts[0] * 3600 + (int)$parts[1] * 60 + (int)$parts[2];
            Log::info("Training {$this->id}: Convertir {$time} (HH:MM:SS) en secondes: {$result}");
            return $result;
        } else if (count($parts) === 2) {
            // Format HH:MM (rétrocompatibilité)
            $result = (int)$parts[0] * 3600 + (int)$parts[1] * 60;
            Log::info("Training {$this->id}: Convertir {$time} (HH:MM) en secondes: {$result}");
            return $result;
        }
        
        Log::warning("Training {$this->id}: Format de durée non reconnu: {$time}");
        return 0;
    }

    public function calculateTotalDuration()
    {
        Log::info("Training ID: " . ($this->id ?? 'nouvelle formation') . ": Début du calcul de la durée totale");

        $totalSeconds = 0;

        // Vérifier si la formation a un ID (c'est-à-dire si elle existe déjà en base)
        if ($this->id) {
            $allCourses = $this->courses()->get();
            Log::info("Training {$this->id}: Nombre de courses trouvés: " . $allCourses->count());

            foreach ($allCourses as $cours) {
                Log::info("Training {$this->id}: Traitement du cours {$cours->id} avec durée: {$cours->duration}");
                $courseSeconds = $this->timeToSeconds($cours->duration);
                $totalSeconds += $courseSeconds;
                Log::info("Training {$this->id}: Après ajout du cours {$cours->id}, total secondes: {$totalSeconds}");
            }
        } else {
            Log::info("Nouvelle formation: pas de cours associés pour le moment");
        }

        $result = $this->secondsToTime($totalSeconds);
        Log::info("Training " . ($this->id ?? 'nouvelle') . ": Durée totale calculée: {$result}");
        return $result;
    }

    // Accesseur pour la durée calculée
    public function getDurationCalculatedAttribute()
    {
        return $this->calculateTotalDuration();
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($training) {
            // Si c'est une nouvelle formation, nous initialisons simplement la durée à 00:00:00
            if (!$training->exists) {
                $training->duration = "00:00:00";
                Log::info("Nouvelle formation: Initialisation de la durée à 00:00:00");
            } else {
                $training->duration = $training->calculateTotalDuration();
                Log::info("Training {$training->id}: Calcul de durée avant sauvegarde: {$training->duration}");
            }
        });
    }
    
    // Accesseur pour vérifier si dans le panier
    public function getInCartAttribute()
    {
        if (!Auth::check()) {
            return false;
        }

        return Cart::where('user_id', Auth::id())
                 ->where('training_id', $this->id)
                 ->exists();
    }

//     public function getFormattedDurationAttribute()
// {
//     if (empty($this->duration)) {
//         return '0 heures';
//     }

//     // Découpe la durée en heures, minutes, secondes
//     $parts = explode(':', $this->duration);
    
//     $hours = (int)$parts[0];
//     $minutes = (int)($parts[1] ?? 0);
//     $seconds = (int)($parts[2] ?? 0);

//     // Construit le texte
//     $text = [];
//     if ($hours > 0) $text[] = $hours.' h';
//     if ($minutes > 0) $text[] = $minutes.' min';
//     if ($seconds > 0) $text[] = $seconds.' s';

//     return !empty($text) ? implode(' ', $text) : '0 heures';
// }

public function getFormattedDurationAttribute()
{
    // Calculer la durée en temps réel plutôt que d'utiliser la valeur stockée
    $duration = $this->calculateTotalDuration();
    
    if (empty($duration)) {
        return '0 heures';
    }

    // Découpe la durée en heures, minutes, secondes
    $parts = explode(':', $duration);
    
    $hours = (int)$parts[0];
    $minutes = (int)($parts[1] ?? 0);
    $seconds = (int)($parts[2] ?? 0);

    // Construit le texte
    $text = [];
    if ($hours > 0) $text[] = $hours.' h';
    if ($minutes > 0) $text[] = $minutes.' min';
    if ($seconds > 0) $text[] = $seconds.' s';

    return !empty($text) ? implode(' ', $text) : '0 heures';
}


}