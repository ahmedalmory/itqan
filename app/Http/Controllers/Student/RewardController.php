<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use App\Models\RewardRedemption;
use App\Models\StudentPoint;
use App\Models\PointsHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RewardController extends Controller
{
    /**
     * Display available rewards for students.
     */
    public function index(Request $request)
    {
        $student = Auth::user();
        $totalPoints = $student->total_points_balance;

        $query = Reward::where('is_active', true);

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('affordable_only') && $request->get('affordable_only') === '1') {
            $query->where('points_cost', '<=', $totalPoints);
        }

        $rewards = $query->orderBy('points_cost')->paginate(12);

        return view('student.rewards.index', compact('rewards', 'totalPoints'));
    }

    /**
     * Display the specified reward.
     */
    public function show(Reward $reward)
    {
        $student = Auth::user();
        $totalPoints = $student->total_points_balance;
        $canAfford = $totalPoints >= $reward->points_cost;
        $isAvailable = $reward->isAvailable();

        return view('student.rewards.show', compact('reward', 'totalPoints', 'canAfford', 'isAvailable'));
    }

    /**
     * Redeem a reward.
     */
    public function redeem(Request $request, Reward $reward)
    {
        $student = Auth::user();
        $totalPoints = $student->total_points_balance;

        // Validation checks
        if (!$reward->isAvailable()) {
            return redirect()->back()
                           ->with('error', t('This reward is not available.'));
        }

        if ($totalPoints < $reward->points_cost) {
            return redirect()->back()
                           ->with('error', t('You do not have enough points for this reward.'));
        }

        try {
            DB::beginTransaction();

            // Create redemption record
            $redemption = RewardRedemption::create([
                'student_id' => $student->id,
                'reward_id' => $reward->id,
                'points_spent' => $reward->points_cost,
                'status' => 'pending',
                'redeemed_at' => now(),
            ]);

            // Deduct points from all student's circles (proportionally)
            $studentPoints = $student->studentPoints()->where('total_points', '>', 0)->get();
            $remainingPointsToDeduct = $reward->points_cost;

            foreach ($studentPoints as $points) {
                if ($remainingPointsToDeduct <= 0) break;

                $pointsToDeductFromThisCircle = min($points->total_points, $remainingPointsToDeduct);
                
                // Update student points
                $points->decrement('total_points', $pointsToDeductFromThisCircle);
                
                // Record points history
                PointsHistory::create([
                    'student_id' => $student->id,
                    'circle_id' => $points->circle_id,
                    'points' => -$pointsToDeductFromThisCircle,
                    'action_type' => 'subtract',
                    'notes' => t('Points used for reward: :reward', ['reward' => $reward->name]),
                    'created_by' => $student->id,
                ]);

                $remainingPointsToDeduct -= $pointsToDeductFromThisCircle;
            }

            // Decrease stock quantity
            $reward->decrement('stock_quantity');

            DB::commit();

            return redirect()->route('student.rewards.redemptions')
                           ->with('success', t('Reward redeemed successfully! Your redemption is pending approval.'));

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                           ->with('error', t('An error occurred while redeeming the reward. Please try again.'));
        }
    }

    /**
     * Display student's redemption history.
     */
    public function redemptions()
    {
        $student = Auth::user();
        $redemptions = $student->rewardRedemptions()
                              ->with('reward')
                              ->orderBy('created_at', 'desc')
                              ->paginate(15);

        return view('student.rewards.redemptions', compact('redemptions'));
    }
} 