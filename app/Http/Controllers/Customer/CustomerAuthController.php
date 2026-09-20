<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class CustomerAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('customer')->check()) {
            return redirect()->route('customer.dashboard');
        }
        return view('customer.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::guard('customer')->attempt($credentials, $remember)) {
            $customer = Auth::guard('customer')->user();

            if ($customer->status !== 'active') {
                Auth::guard('customer')->logout();
                return back()->withErrors(['email' => 'Your account is suspended. Please contact support.']);
            }

            $request->session()->regenerate();
            ActivityLog::record('CUSTOMER_LOGIN', "Customer logged in: {$customer->email}");

            return redirect()->intended(route('customer.dashboard'))
                ->with('success', "Welcome back, {$customer->name}!");
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our customer records.',
        ])->withInput($request->only('email'));
    }

    public function showRegister()
    {
        if (Auth::guard('customer')->check()) {
            return redirect()->route('customer.dashboard');
        }
        return view('customer.auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:customers,email',
            'mobile' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'password' => ['required', 'confirmed', Password::min(6)],
        ]);

        $customer = Customer::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'mobile' => $validated['mobile'],
            'address' => $validated['address'] ?? null,
            'password' => Hash::make($validated['password']),
            'status' => 'active',
        ]);

        Auth::guard('customer')->login($customer);
        ActivityLog::record('CUSTOMER_REGISTER', "New customer registered: {$customer->email}");

        return redirect()->route('customer.dashboard')->with('success', 'Your account has been created successfully!');
    }

    public function showForgotPassword()
    {
        return view('customer.auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $customer = Customer::where('email', $request->email)->first();
        if (!$customer) {
            return back()->withErrors(['email' => 'We could not find an account with that email address.']);
        }

        // Demo password reset notification
        return back()->with('success', 'A password reset instructions link has been sent to your email.');
    }

    public function profile()
    {
        $customer = Auth::guard('customer')->user();
        return view('customer.profile', compact('customer'));
    }

    public function updateProfile(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:customers,email,' . $customer->id,
            'mobile' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $customer->update($validated);
        ActivityLog::record('CUSTOMER_PROFILE_UPDATE', "Customer updated profile: {$customer->email}");

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(6)],
        ]);

        if (!Hash::check($request->current_password, $customer->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $customer->update(['password' => Hash::make($request->password)]);
        ActivityLog::record('CUSTOMER_PASSWORD_CHANGE', "Customer changed password: {$customer->email}");

        return back()->with('success', 'Password updated successfully.');
    }

    public function logout(Request $request)
    {
        if (Auth::guard('customer')->check()) {
            ActivityLog::record('CUSTOMER_LOGOUT', 'Customer logged out: ' . Auth::guard('customer')->user()->email);
        }

        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('customer.login')->with('success', 'You have been logged out.');
    }
}
