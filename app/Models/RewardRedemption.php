<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class RewardRedemption extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'student_id',
        'reward_id',
        'points_spent',
        'status',
        'notes',
        'redeemed_at',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'redeemed_at' => 'datetime',
        'points_spent' => 'integer',
    ];
    
    /**
     * Get the student who redeemed the reward.
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
    
    /**
     * Get the reward that was redeemed.
     */
    public function reward()
    {
        return $this->belongsTo(Reward::class);
    }
    
    /**
     * Check if redemption is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
    
    /**
     * Check if redemption is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }
    
    /**
     * Check if redemption is delivered.
     */
    public function isDelivered(): bool
    {
        return $this->status === 'delivered';
    }
    
    /**
     * Check if redemption is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }
} 