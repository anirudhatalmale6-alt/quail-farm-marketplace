<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:farmer,buyer,investor',
            'phone' => 'nullable|string|max:20',
            'farm_name' => 'required_if:role,farmer|nullable|string|max:255',
            'business_name' => 'required_if:role,buyer|nullable|string|max:255',
            'company_name' => 'required_if:role,investor|nullable|string|max:255',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
            'farm_name' => $validated['farm_name'] ?? null,
            'business_name' => $validated['business_name'] ?? null,
            'company_name' => $validated['company_name'] ?? null,
            'status' => 'pending',
        ]);

        Auth::login($user);

        return match ($user->role) {
            'farmer' => redirect()->route('farmer.dashboard')
                ->with('success', 'Welcome! Your farmer account is pending approval.'),
            'buyer' => redirect()->route('buyer.dashboard')
                ->with('success', 'Welcome! Your buyer account is pending approval.'),
            'investor' => redirect()->route('investor.dashboard')
                ->with('success', 'Welcome! Your investor account is pending approval.'),
            default => redirect('/'),
        };
    }
}
