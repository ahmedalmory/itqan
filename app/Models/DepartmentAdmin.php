<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentAdmin extends Model
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
        'department_id',
        'user_id',
    ];
    
    /**
     * Get the department that the admin belongs to.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    
    /**
     * Get the user record associated with the admin.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 