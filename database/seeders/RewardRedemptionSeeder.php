<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Reward;
use App\Models\RewardRedemption;
use Carbon\Carbon;

class RewardRedemptionSeeder extends Seeder
{
    public function run()
    {
        // Get students and rewards
        $students = User::where('role', 'student')->get();
        $rewards = Reward::where('is_active', true)->get();

        if ($students->isEmpty() || $rewards->isEmpty()) {
            $this->command->warn('No students or active rewards found. Skipping redemption seeder.');
            return;
        }

        $statuses = ['pending', 'approved', 'delivered', 'cancelled'];
        $redemptions = [];

        // Create various redemption scenarios
        for ($i = 0; $i < 25; $i++) {
            $student = $students->random();
            $reward = $rewards->random();
            $status = $statuses[array_rand($statuses)];
            
            // Weight the status distribution (more pending/approved than cancelled)
            if ($i < 8) {
                $status = 'pending';
            } elseif ($i < 15) {
                $status = 'approved';
            } elseif ($i < 20) {  
                $status = 'delivered';
            } else {
                $status = 'cancelled';
            }

            $redeemedAt = Carbon::now()->subDays(rand(1, 30))->subHours(rand(0, 23));
            
            $redemptions[] = [
                'student_id' => $student->id,
                'reward_id' => $reward->id,
                'points_spent' => $reward->points_cost,
                'status' => $status,
                'redeemed_at' => $redeemedAt,
                'notes' => $this->getStatusNotes($status),
                'created_at' => $redeemedAt,
                'updated_at' => $status === 'pending' ? $redeemedAt : $redeemedAt->addHours(rand(1, 48)),
            ];
        }

        // Insert in chunks to avoid memory issues
        foreach (array_chunk($redemptions, 10) as $chunk) {
            RewardRedemption::insert($chunk);
        }

        $this->command->info('✅ Created ' . count($redemptions) . ' reward redemptions');
        
        // Display status summary
        $pendingCount = collect($redemptions)->where('status', 'pending')->count();
        $approvedCount = collect($redemptions)->where('status', 'approved')->count();
        $deliveredCount = collect($redemptions)->where('status', 'delivered')->count();
        $cancelledCount = collect($redemptions)->where('status', 'cancelled')->count();
        
        $this->command->info("   - Pending: {$pendingCount}");
        $this->command->info("   - Approved: {$approvedCount}");
        $this->command->info("   - Delivered: {$deliveredCount}");
        $this->command->info("   - Cancelled: {$cancelledCount}");
    }

    private function getStatusNotes($status)
    {
        $notes = [
            'pending' => [
                'تم تقديم طلب الاستبدال، في انتظار المراجعة',
                'طلب استبدال جديد يحتاج موافقة الإدارة',
                'تحقق من توفر الجائزة قبل الموافقة',
                null, // Some redemptions might not have notes
            ],
            'approved' => [
                'تمت الموافقة، سيتم التحضير للتسليم',
                'موافق عليه، يرجى التواصل مع الطالب',
                'تم تأكيد الاستبدال، جاري التحضير',
                'موافق - متوفر في المخزون',
            ],
            'delivered' => [
                'تم تسليم الجائزة للطالب بنجاح',
                'تم الاستلام وتأكيد التسليم',
                'تم تسليم الجائزة في المكتب الإداري',
                'استلم الطالب الجائزة مع الشكر',
            ],
            'cancelled' => [
                'ألغي بناء على طلب الطالب',
                'لم يتم التسليم - عدم توفر الجائزة',
                'تم الإلغاء لعدم استجابة الطالب',
                'ألغي بسبب عدم كفاية النقاط',
            ]
        ];

        $statusNotes = $notes[$status] ?? [null];
        return $statusNotes[array_rand($statusNotes)];
    }
} 