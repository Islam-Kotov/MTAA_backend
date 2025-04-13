<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PredefinedWorkout extends Model
{
    use HasFactory;

    protected $fillable = [
        'level',
        'title',
        'image',
        'duration',
        'calories',
        'exercise_count',
        'focus',
        'sets_reps',
        'rest',
        'benefits',
    ];

    public function exercises()
    {
        return $this->hasMany(PredefinedWorkoutExercise::class);
    }
}
