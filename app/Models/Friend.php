<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'friend_id',
        'status',
    ];

    // sender
    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // receiver
    public function receiver()
    {
        return $this->belongsTo(User::class, 'friend_id');
    }
}
