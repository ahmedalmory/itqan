<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentSetting extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'setting_key',
        'setting_value',
        'is_active',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];
    
    /**
     * Get a setting value by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getValue(string $key, $default = null)
    {
        $setting = self::where('setting_key', $key)
            ->where('is_active', true)
            ->first();
            
        return $setting ? $setting->setting_value : $default;
    }
    
    /**
     * Update or create a setting.
     *
     * @param string $key
     * @param string $value
     * @param bool $isActive
     * @return \App\Models\PaymentSetting
     */
    public static function updateOrCreateSetting(string $key, string $value, bool $isActive = true)
    {
        return self::updateOrCreate(
            ['setting_key' => $key],
            [
                'setting_value' => $value,
                'is_active' => $isActive,
            ]
        );
    }
} 