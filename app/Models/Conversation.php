<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['seeker_id', 'employer_id', 'last_message_at'];

    public function seeker()   
    { 
        return $this->belongsTo(User::class, 'seeker_id'); 
    }

    public function employer() 
    { 
        return $this->belongsTo(User::class, 'employer_id'); 
    }

    public function messages() 
    { 
        return $this->hasMany(Message::class)->orderBy('created_at'); 
    }

    public function getOtherParticipant(User $user): User
    {
        return $user->id === $this->seeker_id ? $this->employer : $this->seeker;
    }
}
