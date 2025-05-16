<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Run extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'distance',
        'steps',
        'duration',
        'avg_speed',
        'started_at',
        'route',
    ];

    protected $casts = [
        'route' => 'array', 
        'started_at' => 'datetime', 
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
