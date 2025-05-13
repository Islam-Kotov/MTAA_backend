<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklyPlanItem extends Model
{
    use HasFactory;

    protected $fillable = ['weekly_plan_id', 'workout_id', 'sets', 'repetitions'];

    public function weeklyPlan()
    {
        return $this->belongsTo(WeeklyPlan::class);
    }

    public function workout()
    {
        return $this->belongsTo(Workout::class);
    }
}
