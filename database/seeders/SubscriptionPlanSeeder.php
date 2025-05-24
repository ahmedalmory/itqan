<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'type' => 'monthly',
                'lessons_per_month' => 20,
                'price' => 50.00,
                'is_active' => true,
            ],
            [
                'type' => 'quarterly',
                'lessons_per_month' => 20,
                'price' => 135.00, // 10% discount
                'is_active' => true,
            ],
            [
                'type' => 'biannual',
                'lessons_per_month' => 20,
                'price' => 250.00, // 15% discount
                'is_active' => true,
            ],
            [
                'type' => 'annual',
                'lessons_per_month' => 20,
                'price' => 450.00, // 25% discount
                'is_active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::create($plan);
        }
    }
} 