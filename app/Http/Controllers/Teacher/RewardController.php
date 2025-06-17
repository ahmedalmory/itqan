<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use Illuminate\Http\Request;

class RewardController extends Controller
{
    /**
     * Display a listing of the rewards.
     */
    public function index(Request $request)
    {
        $query = Reward::with(['redemptions'])
            ->where('is_active', true)
            ->orderBy('points_cost');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sort functionality
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'points_asc':
                    $query->orderBy('points_cost', 'asc');
                    break;
                case 'points_desc':
                    $query->orderBy('points_cost', 'desc');
                    break;
                case 'name':
                    $query->orderBy('name', 'asc');
                    break;
                default:
                    $query->orderBy('points_cost', 'asc');
            }
        }

        $rewards = $query->paginate(12);

        return view('teacher.rewards.index', compact('rewards'));
    }

    /**
     * Display the specified reward.
     */
    public function show(Reward $reward)
    {
        $reward->load(['redemptions.student']);
        
        return view('teacher.rewards.show', compact('reward'));
    }
} 