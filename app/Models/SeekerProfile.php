<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeekerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'location',
        'job_title',
        'bio',
        'skills',
        'resume_path',
        'availability',
        'expected_salary',
        'education_university',
        'education_degree',
        'education_year',
        'work_company',
        'work_position',
        'work_years',
        'languages',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getSkillsArrayAttribute(): array
    {
        if (!$this->skills) return [];
        return array_map('trim', explode(',', $this->skills));
    }

    public function getLanguagesArrayAttribute(): array
    {
        if (!$this->languages) return [];
        return array_map('trim', explode(',', $this->languages));
    }

    public function completionPercentage(): int
    {
        $fields = [
            'phone', 'location', 'job_title', 'bio', 'skills',
            'resume_path', 'expected_salary', 'education_university',
            'education_degree', 'work_company', 'work_position', 'languages',
        ];
        $filled = collect($fields)->filter(fn($f) => !empty($this->$f))->count();
        return (int) round(($filled / count($fields)) * 100);
    }
}
