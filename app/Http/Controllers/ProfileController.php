<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        $user = Auth::user();
        $countries = Country::orderBy('Order')->get();
        
        return view('profile.show', compact('user', 'countries'));
    }
    
    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone' => 'required|string|max:20',
            'country_id' => 'required|exists:countries,id',
            'age' => 'required|integer|min:5|max:100',
            'gender' => 'required|in:male,female',
            'national_id' => 'nullable|string|max:50',
        ]);
        
        try {
            $user->update($validated);
            
            return back()->with('success', t('profile_updated_successfully'));
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', t('error_updating_profile') . ': ' . $e->getMessage());
        }
    }
} 