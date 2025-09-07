<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.role-selection');
    }

    public function showDonorForm()
    {
        return view('auth.register-donor');
    }

    public function showRecipientForm()
    {
        return view('auth.register-recipient');
    }

    public function storeDonor(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'restaurant_name' => 'required|string|max:255',
            'business_license' => 'required|string|max:255',
            'cuisine_type' => 'required|string|max:255',
            'restaurant_capacity' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
            'terms' => 'required|accepted',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'donor',
            'phone' => $request->phone,
            'address' => $request->address,
            'description' => $request->description,
            'restaurant_name' => $request->restaurant_name,
            'business_license' => $request->business_license,
            'cuisine_type' => $request->cuisine_type,
            'restaurant_capacity' => $request->restaurant_capacity,
            'status' => 'pending',
        ]);

        return redirect()->route('login')->with('success', 
            'Registration successful! Your application is under review and you will receive an email notification once approved.');
    }

    public function storeRecipient(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'organization_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'ngo_registration' => 'required|string|max:255',
            'recipient_capacity' => 'required|integer|min:1',
            'description' => 'required|string',
            'dietary_requirements' => 'nullable|array',
            'needs_preferences' => 'nullable|string',
            'terms' => 'required|accepted',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'recipient',
            'phone' => $request->phone,
            'address' => $request->address,
            'description' => $request->description,
            'organization_name' => $request->organization_name,
            'contact_person' => $request->contact_person,
            'ngo_registration' => $request->ngo_registration,
            'dietary_requirements' => $request->dietary_requirements ?? [],
            'recipient_capacity' => $request->recipient_capacity,
            'needs_preferences' => $request->needs_preferences,
            'status' => 'pending',
        ]);

        return redirect()->route('login')->with('success', 
            'Registration successful! Your application is under review and you will receive an email notification once approved.');
    }
}