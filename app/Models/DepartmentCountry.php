<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class DepartmentCountry extends Pivot
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'department_countries';
    
    /**
     * Indicates if the model has auto-incrementing primary key.
     *
     * @var bool
     */
    public $incrementing = false;
    
    /**
     * The primary key for the model.
     *
     * @var array
     */
    protected $primaryKey = ['department_id', 'country_id'];
    
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
        'country_id',
    ];
    
    /**
     * Get the department associated with the record.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    
    /**
     * Get the country associated with the record.
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }
} 