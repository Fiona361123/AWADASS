<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',         // 'seeker' | 'employer'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // ─── RELATIONSHIPS ────────────────────────────────────────────────────────

    /**
     * A job seeker has one SeekerProfile.
     */
    public function seekerProfile()
    {
        return $this->hasOne(SeekerProfile::class);
    }

    /**
     * An employer has one EmployerProfile.
     */
    public function employerProfile()
    {
        return $this->hasOne(EmployerProfile::class,'user_id','id');
    }

    // ─── HELPERS ─────────────────────────────────────────────────────────────

    public function isSeeker(): bool
    {
        return $this->role === 'seeker';
    }

    public function isEmployer(): bool
    {
        return $this->role === 'employer';
    }

    public function jobPostings()
    {
        return $this->hasMany(JobPosting::class, 'employer_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
    
}
