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

    
    protected $appends = ['photo_url'];

    
    public function getPhotoUrlAttribute()
    {
        return $this->exercise_photo
            ? asset('images/workouts/' . $this->exercise_photo)
            : null;
    }
}
