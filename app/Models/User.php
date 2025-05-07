<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements \Illuminate\Contracts\Auth\CanResetPassword
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, CanResetPassword;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'gender',
        'birthdate',
        'weight',
        'height',
        'profile_completed',
        'photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birthdate'=> 'date',
        ];
    }

    public function getPhotoUrlAttribute()
    {
        return $this->photo_path
            ? route('media.private', ['filepath' => auth()->user()->photo_path])
            : null;
    }

    public function workoutPlan()
    {
        return $this->belongsToMany(Workout::class, 'user_workout_plan')
            ->withPivot(['repetitions', 'sets'])
            ->withTimestamps();
    }

    public function sentFriendRequests()
    {
        return $this->hasMany(Friend::class, 'user_id');
    }

    public function receivedFriendRequests()
    {
        return $this->hasMany(Friend::class, 'friend_id');
    }

    public function friends()
    {
        
        $friendsFrom = Friend::where('friend_id', $this->id)
            ->where('status', 'accepted')
            ->with('sender:id,name,email')
            ->get()
            ->pluck('sender');
    
        
        $friendsTo = Friend::where('user_id', $this->id)
            ->where('status', 'accepted')
            ->with('receiver:id,name,email')
            ->get()
            ->pluck('receiver');
    
        return $friendsFrom->merge($friendsTo);
    }

}
