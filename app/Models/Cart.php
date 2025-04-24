<?php

namespace App\Models;

use App\Models\User;
use App\Models\Training;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'training_ids', // Changé de training_id à training_ids
    ];

    protected $casts = [
        'training_ids' => 'array', // Cast le JSON en array PHP
    ];
    
    // Relation avec l'utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation avec les formations (maintenant plusieurs formations via JSON)
    public function trainings()
    {
        // Si vous voulez accéder aux objets Training complets
        // Note: Cette approche peut être inefficace pour de grandes listes
        return Training::whereIn('id', $this->training_ids ?: []);
    }

    // Méthodes utilitaires pour gérer les formations
    public function addTraining($trainingId)
    {
        $trainings = $this->training_ids ?: [];
        if (!in_array($trainingId, $trainings)) {
            $trainings[] = $trainingId;
            $this->training_ids = $trainings;
        }
        return $this;
    }

    public function removeTraining($trainingId)
    {
        $trainings = $this->training_ids ?: [];
        $this->training_ids = array_diff($trainings, [$trainingId]);
        return $this;
    }
    public function getFormations()
    {
        if (empty($this->training_ids)) {
            return collect([]);
        }
        return Training::whereIn('id', $this->training_ids)->get();
    }
    public function hasTraining($trainingId)
    {
        return in_array($trainingId, $this->training_ids ?: []);
    }
    
}