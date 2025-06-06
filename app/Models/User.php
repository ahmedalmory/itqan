<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'age',
        'gender',
        'national_id',
        'role',
        'is_active',
        'country_id',
        'preferred_time',
        'department_id',
        'study_circle_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    /**
     * Get the country that the user belongs to.
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    /**
     * Get the department admins for the user.
     */
    public function departmentAdmins()
    {
        return $this->hasMany(DepartmentAdmin::class);
    }
    
    /**
     * Get the departments that the user is an admin for.
     */
    public function adminDepartments()
    {
        return $this->belongsToMany(Department::class, 'department_admins');
    }
    
    /**
     * Get the study circles that the user teaches.
     */
    public function taughtCircles()
    {
        return $this->hasMany(StudyCircle::class, 'teacher_id');
    }
    
    /**
     * Get the study circles that the user supervises.
     */
    public function supervisedCircles()
    {
        return $this->hasMany(StudyCircle::class, 'supervisor_id');
    }
    
    /**
     * Get the circles that the student belongs to.
     */
    public function circles()
    {
        return $this->belongsToMany(StudyCircle::class, 'circle_students', 'student_id', 'circle_id');
    }
    
    /**
     * Get the daily reports for the student.
     */
    public function dailyReports()
    {
        return $this->hasMany(DailyReport::class, 'student_id');
    }
    
    /**
     * Get the student's points.
     */
    public function studentPoints()
    {
        return $this->hasMany(StudentPoint::class, 'student_id');
    }
    
    /**
     * Get the student's points history.
     */
    public function pointsHistory()
    {
        return $this->hasMany(PointsHistory::class, 'student_id');
    }
    
    /**
     * Get the student's subscriptions.
     */
    public function subscriptions()
    {
        return $this->hasMany(StudentSubscription::class, 'student_id');
    }
    
    /**
     * Check if the user is a super admin.
     */
    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }
    
    /**
     * Check if the user is a department admin.
     */
    public function isDepartmentAdmin()
    {
        return $this->role === 'department_admin';
    }
    
    /**
     * Check if the user is a teacher.
     */
    public function isTeacher()
    {
        return $this->role === 'teacher';
    }
    
    /**
     * Check if the user is a supervisor.
     */
    public function isSupervisor()
    {
        return $this->role === 'supervisor';
    }
    
    /**
     * Check if the user is a student.
     */
    public function isStudent()
    {
        return $this->role === 'student';
    }

    /**
     * Get the department that the user belongs to.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the study circle that the user belongs to.
     */
    public function studyCircle()
    {
        return $this->belongsTo(StudyCircle::class, 'study_circle_id');
    }
}
