<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show($id)
    {
        $user = User::withCount(['products', 'reviews'])
            ->findOrFail($id);

        $products = $user->products()->where('status', 'active')->latest()->take(6)->get();
        $reviews = $user->reviews()->with('reviewer:id,name,avatar')->latest()->take(5)->get();

        return view('profile.show', compact('user', 'products', 'reviews'));
    }

    public function edit()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'website' => 'nullable|url|max:255',
            'farm_name' => 'nullable|string|max:255',
            'business_name' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'investment_budget' => 'nullable|numeric|min:0',
            'investment_interests' => 'nullable|string|max:1000',
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $validated['profile_picture'] = $request->file('profile_picture')->store('profiles', 'public');
        }

        $user->update($validated);

        return redirect()->route('profile.show', $user->id)->with('success', 'Profile updated successfully!');
    }
}
