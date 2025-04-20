<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPoint extends Model
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
        'student_id',
        'circle_id',
        'total_points',
        'last_updated',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'last_updated' => 'datetime',
    ];
    
    /**
     * Get the student that owns the points.
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
    
    /**
     * Get the circle that the points belong to.
     */
    public function circle()
    {
        return $this->belongsTo(StudyCircle::class, 'circle_id');
    }
    
    /**
     * Get the points history for this student and circle.
     */
    public function history()
    {
        return $this->hasMany(PointsHistory::class, 'student_id', 'student_id')
            ->where('circle_id', $this->circle_id);
    }
}