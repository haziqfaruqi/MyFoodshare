<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('restaurant.profile.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('restaurant.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'restaurant_name' => 'nullable|string|max:255',
            'cuisine_type' => 'nullable|string|max:100',
            'restaurant_capacity' => 'nullable|integer|min:1',
            'description' => 'nullable|string|max:1000',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'restaurant_name' => $request->restaurant_name,
            'cuisine_type' => $request->cuisine_type,
            'restaurant_capacity' => $request->restaurant_capacity,
            'description' => $request->description,
        ]);

        return redirect()->route('restaurant.profile.show')
            ->with('success', 'Profile updated successfully!');
    }
}
