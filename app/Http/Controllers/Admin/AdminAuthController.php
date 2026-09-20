<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            $request->session()->regenerate();

            ActivityLog::record('ADMIN_LOGIN', 'Admin logged in: ' . Auth::guard('admin')->user()->email);

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Welcome back, ' . Auth::guard('admin')->user()->name . '!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our admin records.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            ActivityLog::record('ADMIN_LOGOUT', 'Admin logged out: ' . Auth::guard('admin')->user()->email);
        }

        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'You have been successfully logged out.');
    }

    public function profile()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.profile', compact('admin'));
    }

    public function updateProfile(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:admins,email,' . $admin->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $admin->update($validated);
        ActivityLog::record('ADMIN_PROFILE_UPDATE', 'Admin updated profile details');

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => 'Current password does not match.']);
        }

        $admin->update([
            'password' => Hash::make($request->password),
        ]);

        ActivityLog::record('ADMIN_PASSWORD_CHANGE', 'Admin changed password');

        return back()->with('success', 'Password changed successfully.');
    }
}
