<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lessons_per_month',
        'price',
        'is_active',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];
    
    /**
     * Get the student subscriptions for the plan.
     */
    public function subscriptions()
    {
        return $this->hasMany(StudentSubscription::class, 'plan_id');
    }
    
    /**
     * Scope a query to only include active plans.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}