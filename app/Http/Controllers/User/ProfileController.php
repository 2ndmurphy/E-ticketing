<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
     /**
     * Display user's profile.
     */
    public function index()
    {
        $user = Auth::user();
        // return view('user.profile.index', compact('user'));
    }

    /**
     * Show edit profile form.
     */
    public function edit()
    {
        $user = Auth::user();
        // return view('user.profile.edit', compact('user'));
    }

    /**
     * Update profile info.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        // return redirect()->route('user.profile.index')
        //     ->with('success', 'Profile updated successfully!');
    }

    /**
     * Show change password form.
     */
    public function editPassword()
    {
        // return view('user.profile.password');
    }

    /**
     * Update user password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Incorrect current password.']);
        }

        $user->password = Hash::make($validated['new_password']);
        $user->save();

        // return redirect()->route('user.profile.index')
        //     ->with('success', 'Password changed successfully!');
    }
}
