<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PredefinedWorkoutExercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'predefined_workout_id',
        'title',
        'image',
        'reps_sets',
        'guide',
    ];

    public function workout()
    {
        return $this->belongsTo(PredefinedWorkout::class);
    }
}
