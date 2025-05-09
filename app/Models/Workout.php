<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workout extends Model
{
    use HasFactory;

    protected $fillable = [
        'exercise_category',
        'exercise_type',
        'exercise_name',
        'main_muscles',
        'equipment_req',
        'execution_guide',
        'exercise_photo',
    ];


    public function users()
    {
        return $this->belongsToMany(User::class, 'user_workout_plan')
            ->withPivot(['repetitions', 'sets'])
            ->withTimestamps();
    }
}
