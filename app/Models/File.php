<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;
    
        protected $fillable = [
            'name',
            'file_path',
            'file_type',
            'file_size',
            'lesson_id'
        ];
        
        /**
         * Obtenir la leçon à laquelle appartient ce fichier.
         */
        public function lesson()
        {
            return $this->belongsTo(Lesson::class);
        }
}
