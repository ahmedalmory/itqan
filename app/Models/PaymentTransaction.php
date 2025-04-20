<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subscription_id',
        'transaction_id',
        'payment_method',
        'amount',
        'currency',
        'status',
        'paymob_order_id',
        'paymob_transaction_id',
        'paymob_response',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'amount' => 'decimal:2',
    ];
    
    /**
     * Get the subscription that the transaction belongs to.
     */
    public function subscription()
    {
        return $this->belongsTo(StudentSubscription::class, 'subscription_id');
    }
    
    /**
     * Scope a query to only include successful transactions.
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }
    
    /**
     * Scope a query to only include pending transactions.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    
    /**
     * Scope a query to only include failed transactions.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
} 