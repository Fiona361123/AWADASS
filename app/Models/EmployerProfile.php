<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'company_name',
        'industry',
        'company_size',
        'website',
        'description',
        'logo_path',
        'location',
        'address',
        'year_established',
        'linkedin',
        'facebook',
        'twitter',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function completionPercentage(): int
    {
        $fields = [
            'phone', 'company_name', 'industry', 'company_size',
            'website', 'description', 'location', 'address',
            'year_established', 'linkedin',
        ];
        $filled = collect($fields)->filter(fn($f) => !empty($this->$f))->count();
        return (int) round(($filled / count($fields)) * 100);
    }

    public function jobs()
    {
        return $this->hasMany(JobPosting::class, 'employer_id', 'user_id');
    }
}
