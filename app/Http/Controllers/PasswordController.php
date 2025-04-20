<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    /**
     * Show the change password form.
     *
     * @return \Illuminate\View\View
     */
    public function showChangePasswordForm()
    {
        return view('profile.change-password');
    }
    
    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        // Check if current password is correct
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors([
                'current_password' => t('current_password_incorrect'),
            ]);
        }
        
        try {
            $user->update([
                'password' => Hash::make($validated['password']),
            ]);
            
            return back()->with('success', t('password_updated_successfully'));
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', t('error_updating_password') . ': ' . $e->getMessage());
        }
    }
} 