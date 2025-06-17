<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RewardRedemption;
use Illuminate\Http\Request;

class RewardRedemptionController extends Controller
{
    /**
     * Display a listing of reward redemptions.
     */
    public function index(Request $request)
    {
        $query = RewardRedemption::with(['student', 'reward']);

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('reward', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $redemptions = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.reward-redemptions.index', compact('redemptions'));
    }

    /**
     * Display the specified redemption.
     */
    public function show(RewardRedemption $redemption)
    {
        $redemption->load(['student', 'reward']);
        
        return view('admin.reward-redemptions.show', compact('redemption'));
    }

    /**
     * Update the redemption status.
     */
    public function updateStatus(Request $request, RewardRedemption $redemption)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,delivered,cancelled',
            'notes' => 'nullable|string',
        ]);

        $oldStatus = $redemption->status;
        $newStatus = $request->status;

        // Handle status change logic
        if ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
            // If reactivating a cancelled redemption, check if reward is still available
            if (!$redemption->reward->isAvailable()) {
                return redirect()->back()
                                ->with('error', t('Reward is no longer available.'));
            }
        }

        $redemption->update([
            'status' => $newStatus,
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.reward-redemptions.index')
                         ->with('success', t('Redemption status updated successfully.'));
    }
} 