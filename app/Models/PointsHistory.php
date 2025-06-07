<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PointsHistory extends Model
{
    use HasFactory;
    
    protected $table = 'points_history';

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
        'student_id',
        'circle_id',
        'points',
        'action_type',
        'notes',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::creating(function ($pointsHistory) {
            if (!isset($pointsHistory->created_by)) {
                $pointsHistory->created_by = Auth::id();
            }
        });
    }
    
    /**
     * Get the student that the points history belongs to.
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
    
    /**
     * Get the circle that the points history belongs to.
     */
    public function circle()
    {
        return $this->belongsTo(StudyCircle::class, 'circle_id');
    }
    
    /**
     * Get the user who created the points record.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
} 