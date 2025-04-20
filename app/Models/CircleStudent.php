<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CircleStudent extends Pivot
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'circle_students';
    
    /**
     * Indicates if the model should be auto-incremented.
     *
     * @var bool
     */
    public $incrementing = true;
    
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
        'circle_id',
        'student_id',
    ];
    
    /**
     * Get the student associated with the circle enrollment.
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
    
    /**
     * Get the circle associated with the enrollment.
     */
    public function circle()
    {
        return $this->belongsTo(StudyCircle::class, 'circle_id');
    }
} 