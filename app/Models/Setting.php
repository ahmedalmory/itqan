<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
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
        'site_name',
        'site_description',
        'admin_email',
        'maintenance_mode',
        'smtp_host',
        'smtp_port',
        'smtp_username',
        'smtp_password',
        'smtp_encryption',
        'academic_year',
        'semester',
        'registration_open',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'maintenance_mode' => 'boolean',
        'registration_open' => 'boolean',
    ];
    
    /**
     * Get a setting instance (creates one if it doesn't exist).
     *
     * @return \App\Models\Setting
     */
    public static function getInstance()
    {
        $setting = self::first();
        
        if (!$setting) {
            $setting = self::create([
                'site_name' => 'Quran Study Circles',
                'site_description' => 'Welcome to our Quran Study Circles Management System',
                'maintenance_mode' => false,
                'registration_open' => true,
            ]);
        }
        
        return $setting;
    }
} 