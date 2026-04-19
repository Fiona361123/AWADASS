<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationDocument extends Model
{
    use HasFactory;

    protected $appends = ['url'];

    protected $fillable = [
        'application_id',
        'file_path'
    ];

    // link back to application
    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function getUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }
}
