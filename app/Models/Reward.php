<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
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
        'points_cost',
        'image',
        'stock_quantity',
        'is_active',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'points_cost' => 'integer',
        'stock_quantity' => 'integer',
    ];
    
    /**
     * Get the redemptions for this reward.
     */
    public function redemptions()
    {
        return $this->hasMany(RewardRedemption::class);
    }
    
    /**
     * Check if reward is available for redemption.
     */
    public function isAvailable(): bool
    {
        return $this->is_active && $this->stock_quantity > 0;
    }
    
    /**
     * Get the number of times this reward has been redeemed.
     */
    public function getRedeemedCountAttribute(): int
    {
        return $this->redemptions()->where('status', '!=', 'cancelled')->count();
    }
    
    /**
     * Get the remaining stock.
     */
    public function getRemainingStockAttribute(): int
    {
        return max(0, $this->stock_quantity - $this->redeemed_count);
    }
} 