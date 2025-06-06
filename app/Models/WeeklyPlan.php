<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklyPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'day_of_week',
        'title',
        'description',
        'scheduled_time',
    ];

    protected $casts = [
        'scheduled_time' => 'string', 
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(WeeklyPlanItem::class);
    }
}
