<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user()
        ]);
    }

    // ── UPDATE PROFILE ─────────────────────────────────────────────
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . Auth::id(),
        ]);

        $user = $request->user();

        $user->update([
            'fullname' => $request->fullname,
            'email'    => $request->email,
        ]);

        return redirect()->route('profile.edit')
            ->with('success', 'Profile updated successfully.');
    }

    // ── CHANGE PASSWORD ────────────────────────────────────────────
    public function changePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Current password is incorrect.'
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('profile.edit')
            ->with('success', 'Password changed successfully.');
    }
}