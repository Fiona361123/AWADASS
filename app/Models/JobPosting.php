<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPosting extends Model
{
    use HasFactory;

    protected $fillable = [
        'employer_id', 
        'title', 
        'description', 
        'requirements', 
        'salary_min',   
        'salary_max', 
        'location', 
        'is_active'
    ];

    public function employer()
    {
        return $this->belongsTo(User::class, 'employer_id');
    }
}