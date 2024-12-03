<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPreference extends Model
{
    use HasFactory, SoftDeletes;

    private $guard = ['id'];

    protected $casts = [
        'preferred_categories' => 'array',
        'preferred_sources' => 'array',
        'preferred_authors' => 'array',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
