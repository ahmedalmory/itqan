<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;
    
    /**
     * Indicates if the model should use updated_at timestamp.
     *
     * @var bool
     */
    const UPDATED_AT = null;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
        'direction',
        'is_active',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];
    
    /**
     * Get the translations for the language.
     */
    public function translations()
    {
        return $this->hasMany(Translation::class);
    }
    
    /**
     * Scope a query to only include active languages.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
} 