<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'alt_name',
        'order',
        'country_code',
    ];
    
    /**
     * Get the users from this country.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'country_id', 'id');
    }
    
    /**
     * Get the departments that allow this country.
     */
    public function departments()
    {
        return $this->belongsToMany(Department::class, 'department_countries', 'country_id', 'department_id');
    }
} 