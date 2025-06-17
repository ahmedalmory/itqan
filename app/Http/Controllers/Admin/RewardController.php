<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RewardController extends Controller
{
    /**
     * Display a listing of the rewards.
     */
    public function index(Request $request)
    {
        $query = Reward::query();

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $status = $request->get('status');
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $rewards = $query->withCount('redemptions')
                        ->orderBy('created_at', 'desc')
                        ->paginate(15);

        return view('admin.rewards.index', compact('rewards'));
    }

    /**
     * Show the form for creating a new reward.
     */
    public function create()
    {
        return view('admin.rewards.create');
    }

    /**
     * Store a newly created reward in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'points_cost' => 'required|integer|min:1',
            'stock_quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        $data = $request->only(['name', 'description', 'points_cost', 'stock_quantity', 'is_active']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('rewards', 'public');
        }

        Reward::create($data);

        return redirect()->route('admin.rewards.index')
                         ->with('success', t('Reward created successfully.'));
    }

    /**
     * Display the specified reward.
     */
    public function show(Reward $reward)
    {
        $reward->load(['redemptions.student']);
        
        return view('admin.rewards.show', compact('reward'));
    }

    /**
     * Show the form for editing the specified reward.
     */
    public function edit(Reward $reward)
    {
        return view('admin.rewards.edit', compact('reward'));
    }

    /**
     * Update the specified reward in storage.
     */
    public function update(Request $request, Reward $reward)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'points_cost' => 'required|integer|min:1',
            'stock_quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        $data = $request->only(['name', 'description', 'points_cost', 'stock_quantity', 'is_active']);
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($reward->image) {
                Storage::disk('public')->delete($reward->image);
            }
            $data['image'] = $request->file('image')->store('rewards', 'public');
        }

        $reward->update($data);

        return redirect()->route('admin.rewards.index')
                         ->with('success', t('Reward updated successfully.'));
    }

    /**
     * Remove the specified reward from storage.
     */
    public function destroy(Reward $reward)
    {
        // Check if reward has any redemptions
        if ($reward->redemptions()->count() > 0) {
            return redirect()->route('admin.rewards.index')
                             ->with('error', t('Cannot delete reward with existing redemptions.'));
        }

        // Delete image if exists
        if ($reward->image) {
            Storage::disk('public')->delete($reward->image);
        }

        $reward->delete();

        return redirect()->route('admin.rewards.index')
                         ->with('success', t('Reward deleted successfully.'));
    }
} 