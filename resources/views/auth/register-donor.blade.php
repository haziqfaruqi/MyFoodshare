@extends('layouts.guest')

@section('title', 'Register as Food Donor - MyFoodshare')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Register as Food Donor</h1>
            <p class="text-gray-600">Join our community of restaurants and food businesses helping to reduce food waste</p>
        </div>

        <!-- Registration Form -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <form method="POST" action="{{ route('register.donor.store') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="role" value="donor">

                <!-- Basic Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Contact Person Name *</label>
                            <input type="text" id="name" name="name" required 
                                   value="{{ old('name') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                   placeholder="e.g., John Doe">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                            <input type="email" id="email" name="email" required 
                                   value="{{ old('email') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                   placeholder="contact@restaurant.com">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number *</label>
                            <input type="tel" id="phone" name="phone" required 
                                   value="{{ old('phone') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                   placeholder="+60123456789">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                            <input type="password" id="password" name="password" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                   placeholder="Minimum 8 characters">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password *</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                   placeholder="Re-enter password">
                        </div>
                    </div>
                </div>

                <!-- Restaurant Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Restaurant Information</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="restaurant_name" class="block text-sm font-medium text-gray-700 mb-1">Restaurant/Business Name *</label>
                            <input type="text" id="restaurant_name" name="restaurant_name" required 
                                   value="{{ old('restaurant_name') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                   placeholder="e.g., Golden Spoon Restaurant">
                            @error('restaurant_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="cuisine_type" class="block text-sm font-medium text-gray-700 mb-1">Cuisine Type *</label>
                                <select id="cuisine_type" name="cuisine_type" required 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                                    <option value="">Select cuisine type</option>
                                    <option value="Malaysian" {{ old('cuisine_type') === 'Malaysian' ? 'selected' : '' }}>Malaysian</option>
                                    <option value="Chinese" {{ old('cuisine_type') === 'Chinese' ? 'selected' : '' }}>Chinese</option>
                                    <option value="Indian" {{ old('cuisine_type') === 'Indian' ? 'selected' : '' }}>Indian</option>
                                    <option value="Western" {{ old('cuisine_type') === 'Western' ? 'selected' : '' }}>Western</option>
                                    <option value="Italian" {{ old('cuisine_type') === 'Italian' ? 'selected' : '' }}>Italian</option>
                                    <option value="Japanese" {{ old('cuisine_type') === 'Japanese' ? 'selected' : '' }}>Japanese</option>
                                    <option value="Thai" {{ old('cuisine_type') === 'Thai' ? 'selected' : '' }}>Thai</option>
                                    <option value="International" {{ old('cuisine_type') === 'International' ? 'selected' : '' }}>International</option>
                                    <option value="Other" {{ old('cuisine_type') === 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('cuisine_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="restaurant_capacity" class="block text-sm font-medium text-gray-700 mb-1">Seating Capacity</label>
                                <input type="number" id="restaurant_capacity" name="restaurant_capacity" min="1" 
                                       value="{{ old('restaurant_capacity') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                       placeholder="e.g., 120">
                                @error('restaurant_capacity')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Business Address *</label>
                            <textarea id="address" name="address" rows="3" required 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                      placeholder="Enter complete business address including city and state">{{ old('address') }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Business Description</label>
                            <textarea id="description" name="description" rows="3" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                      placeholder="Tell us about your restaurant, specialties, and why you want to join MyFoodshare">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Legal Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Legal Information</h3>
                    <div>
                        <label for="business_license" class="block text-sm font-medium text-gray-700 mb-1">Business License Number *</label>
                        <input type="text" id="business_license" name="business_license" required 
                               value="{{ old('business_license') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                               placeholder="e.g., BL2024001">
                        <p class="mt-1 text-xs text-gray-500">Required for compliance with Malaysian Food Act 1983</p>
                        @error('business_license')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Terms and Conditions -->
                <div>
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="terms" name="terms" type="checkbox" required 
                                   class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="terms" class="font-medium text-gray-700">
                                I agree to the Terms and Conditions *
                            </label>
                            <p class="text-gray-500">
                                By registering, I confirm that all information provided is accurate and I comply with Malaysian Food Act 1983. I understand that my application will be reviewed by MyFoodshare administrators.
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
                            class="bg-green-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-700 transition-colors flex items-center">
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
                    Your application will be reviewed by our team within 2-3 business days. You'll receive an email notification with the approval status and next steps.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection