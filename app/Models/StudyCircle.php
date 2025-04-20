<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyCircle extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'study_circles';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'department_id',
        'teacher_id',
        'supervisor_id',
        'max_students',
        'whatsapp_group',
        'telegram_group',
        'age_from',
        'age_to',
        'circle_time',
    ];
    
    /**
     * Get the department that the circle belongs to.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    
    /**
     * Get the teacher that leads the circle.
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
    
    /**
     * Get the supervisor of the circle.
     */
    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }
    
    /**
     * Get the students belonging to the circle.
     */
    public function students()
    {
        return $this->belongsToMany(User::class, 'circle_students', 'circle_id', 'student_id')->withTimestamps();;
    }
    
    /**
     * Get the student points for the circle.
     */
    public function studentPoints()
    {
        return $this->hasMany(StudentPoint::class, 'circle_id');
    }
    
    /**
     * Get the points history for the circle.
     */
    public function pointsHistory()
    {
        return $this->hasMany(PointsHistory::class, 'circle_id');
    }
    
    /**
     * Get the subscriptions for the circle.
     */
    public function subscriptions()
    {
        return $this->hasMany(StudentSubscription::class, 'circle_id');
    }
} 