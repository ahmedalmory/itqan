<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surah extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'surahs';
    
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
        'name',
        'total_verses',
    ];
    
    /**
     * Get the daily reports where this surah is the starting point of memorization.
     */
    public function memorizationStartReports()
    {
        return $this->hasMany(DailyReport::class, 'memorization_from_surah');
    }
    
    /**
     * Get the daily reports where this surah is the ending point of memorization.
     */
    public function memorizationEndReports()
    {
        return $this->hasMany(DailyReport::class, 'memorization_to_surah');
    }
} 