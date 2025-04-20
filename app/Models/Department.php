<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'student_gender',
        'work_friday',
        'work_saturday',
        'work_sunday',
        'work_monday',
        'work_tuesday',
        'work_wednesday',
        'work_thursday',
        'monthly_fees',
        'quarterly_fees',
        'biannual_fees',
        'annual_fees',
        'restrict_countries',
        'registration_open'
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'work_friday' => 'boolean',
        'work_saturday' => 'boolean',
        'work_sunday' => 'boolean',
        'work_monday' => 'boolean',
        'work_tuesday' => 'boolean',
        'work_wednesday' => 'boolean',
        'work_thursday' => 'boolean',
        'restrict_countries' => 'boolean',
    ];
    
    /**
     * Get the admins for the department.
     */
    public function admins()
    {
        return $this->belongsToMany(User::class, 'department_admins');
    }
    
    /**
     * Get the countries allowed for the department.
     */
    public function allowedCountries()
    {
        return $this->belongsToMany(Country::class, 'department_countries', 'department_id', 'country_id');
    }
    
    /**
     * Get the circles for the department.
     */
    public function circles()
    {
        return $this->hasMany(StudyCircle::class);
    }

    /**
     * Get the students for the department through circles.
     */
    public function students()
    {
        return $this->hasManyThrough(User::class, StudyCircle::class, 'department_id', 'id', 'id', 'id');
    }
} 