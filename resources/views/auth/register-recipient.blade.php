@extends('layouts.guest')

@section('title', 'Register as Food Recipient - MyFoodshare')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Register as Food Recipient</h1>
            <p class="text-gray-600">Join our network of NGOs and organizations helping communities in need</p>
        </div>

        <!-- Registration Form -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <form method="POST" action="{{ route('register.recipient.store') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="role" value="recipient">

                <!-- Basic Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Your Name *</label>
                            <input type="text" id="name" name="name" required 
                                   value="{{ old('name') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="e.g., Sarah Ahmad">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                            <input type="email" id="email" name="email" required 
                                   value="{{ old('email') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="contact@ngo.org">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number *</label>
                            <input type="tel" id="phone" name="phone" required 
                                   value="{{ old('phone') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="+60123456789">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                            <input type="password" id="password" name="password" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Minimum 8 characters">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password *</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Re-enter password">
                        </div>
                    </div>
                </div>

                <!-- Organization Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Organization Information</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="organization_name" class="block text-sm font-medium text-gray-700 mb-1">Organization Name *</label>
                            <input type="text" id="organization_name" name="organization_name" required 
                                   value="{{ old('organization_name') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="e.g., Hope Foundation Malaysia">
                            @error('organization_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-1">Main Contact Person *</label>
                                <input type="text" id="contact_person" name="contact_person" required 
                                       value="{{ old('contact_person') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Person responsible for coordination">
                                @error('contact_person')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="recipient_capacity" class="block text-sm font-medium text-gray-700 mb-1">Daily Serving Capacity *</label>
                                <input type="number" id="recipient_capacity" name="recipient_capacity" min="1" required
                                       value="{{ old('recipient_capacity') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="e.g., 200">
                                <p class="mt-1 text-xs text-gray-500">How many people do you serve daily?</p>
                                @error('recipient_capacity')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Organization Address *</label>
                            <textarea id="address" name="address" rows="3" required 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Enter complete address including city and state">{{ old('address') }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Organization Description *</label>
                            <textarea id="description" name="description" rows="3" required
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Tell us about your organization, mission, and the community you serve">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Dietary Requirements & Preferences -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Dietary Requirements & Preferences</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Dietary Requirements for Your Community
                            </label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                @php
                                    $dietaryOptions = ['Halal', 'Vegetarian', 'Vegan', 'Kosher', 'Gluten-Free', 'Nut-Free', 'Dairy-Free', 'Low-Sodium', 'Diabetic-Friendly'];
                                    $oldDietaryInfo = old('dietary_requirements', []);
                                @endphp
                                @foreach($dietaryOptions as $option)
                                    <label class="flex items-center">
                                        <input
                                            type="checkbox"
                                            name="dietary_requirements[]"
                                            value="{{ $option }}"
                                            {{ in_array($option, $oldDietaryInfo) ? 'checked' : '' }}
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                        />
                                        <span class="ml-2 text-sm text-gray-700">{{ $option }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <label for="needs_preferences" class="block text-sm font-medium text-gray-700 mb-1">Special Needs & Preferences</label>
                            <textarea id="needs_preferences" name="needs_preferences" rows="3" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Describe any specific food preferences, preparation requirements, or scheduling constraints">{{ old('needs_preferences') }}</textarea>
                            @error('needs_preferences')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Legal Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Legal Information</h3>
                    <div>
                        <label for="ngo_registration" class="block text-sm font-medium text-gray-700 mb-1">NGO Registration Number *</label>
                        <input type="text" id="ngo_registration" name="ngo_registration" required 
                               value="{{ old('ngo_registration') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               placeholder="e.g., NGO2024001">
                        <p class="mt-1 text-xs text-gray-500">Required for verification as a legitimate non-profit organization</p>
                        @error('ngo_registration')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Terms and Conditions -->
                <div>
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="terms" name="terms" type="checkbox" required 
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="terms" class="font-medium text-gray-700">
                                I agree to the Terms and Conditions *
                            </label>
                            <p class="text-gray-500">
                                By registering, I confirm that all information provided is accurate and that our organization is a legitimate non-profit dedicated to serving communities in need. I understand that my application will be reviewed by MyFoodshare administrators.
                            </p>
                        </div>
                    </div>
                    @error('terms')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('register') }}" 
                       class="text-gray-600 hover:text-gray-800 font-medium">
                        ← Back to role selection
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors flex items-center">
                        Submit Application
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <!-- Help Text -->
        <div class="mt-8 text-center">
            <div class="bg-blue-50 rounded-lg p-4">
                <h4 class="text-sm font-medium text-blue-800 mb-1">Application Review Process</h4>
                <p class="text-sm text-blue-600">
                    Your application will be carefully reviewed by our team within 2-3 business days. We verify all NGO registrations and organizational details to ensure the integrity of our platform.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection