<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSubscription extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'student_id',
        'circle_id',
        'plan_id',
        'duration_months',
        'start_date',
        'end_date',
        'total_amount',
        'payment_status',
        'payment_method',
        'is_active',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];
    
    /**
     * Get the student that owns the subscription.
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
    
    /**
     * Get the circle that the subscription belongs to.
     */
    public function circle()
    {
        return $this->belongsTo(StudyCircle::class, 'circle_id');
    }
    
    /**
     * Get the plan that the subscription is based on.
     */
    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }
    
    /**
     * Get the payment transactions for the subscription.
     */
    public function paymentTransactions()
    {
        return $this->hasMany(PaymentTransaction::class, 'subscription_id');
    }
    
    /**
     * Scope a query to only include active subscriptions.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    /**
     * Scope a query to only include paid subscriptions.
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }
    
    /**
     * Scope a query to only include pending subscriptions.
     */
    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }
} 