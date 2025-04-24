<?php

namespace App\Models;

use App\Models\Formation;
use App\Models\Training;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'training_id',
        'obtained_date',
        'status',
    ];

    // Relation avec l'étudiant
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation avec la formation
    public function training()
    {
        return $this->belongsTo(Training::class);
    }

}
