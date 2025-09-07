@extends('layouts.restaurant')

@section('title', 'Create Listing - MyFoodshare')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('restaurant.dashboard') }}" class="flex items-center text-gray-600 hover:text-gray-900 mb-4">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Dashboard
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Create Food Listing</h1>
            <p class="text-gray-600 mt-1">Add surplus food to help reduce waste and feed the community</p>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-8">
            <form method="POST" action="{{ route('restaurant.listings.store') }}" enctype="multipart/form-data" class="space-y-8">
                @csrf
                
                <!-- Basic Information -->
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Basic Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="food_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Food Name *
                            </label>
                            <input
                                type="text"
                                id="food_name"
                                name="food_name"
                                required
                                value="{{ old('food_name') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                placeholder="e.g., Fresh Sandwiches, Pasta Salad"
                            />
                            @error('food_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                Category *
                            </label>
                            <select
                                id="category"
                                name="category"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                            >
                                <option value="">Select a category</option>
                                <option value="Prepared Meals" {{ old('category') === 'Prepared Meals' ? 'selected' : '' }}>Prepared Meals</option>
                                <option value="Bread & Bakery" {{ old('category') === 'Bread & Bakery' ? 'selected' : '' }}>Bread & Bakery</option>
                                <option value="Fruits & Vegetables" {{ old('category') === 'Fruits & Vegetables' ? 'selected' : '' }}>Fruits & Vegetables</option>
                                <option value="Dairy Products" {{ old('category') === 'Dairy Products' ? 'selected' : '' }}>Dairy Products</option>
                                <option value="Meat & Seafood" {{ old('category') === 'Meat & Seafood' ? 'selected' : '' }}>Meat & Seafood</option>
                                <option value="Beverages" {{ old('category') === 'Beverages' ? 'selected' : '' }}>Beverages</option>
                                <option value="Other" {{ old('category') === 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description
                            </label>
                            <textarea
                                id="description"
                                name="description"
                                rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                placeholder="Describe the food items, ingredients, preparation method, etc."
                            >{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Quantity Information -->
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Quantity & Packaging</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                Quantity *
                            </label>
                            <input
                                type="number"
                                id="quantity"
                                name="quantity"
                                required
                                min="1"
                                value="{{ old('quantity') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                placeholder="e.g., 25"
                            />
                            @error('quantity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="unit" class="block text-sm font-medium text-gray-700 mb-2">
                                Unit *
                            </label>
                            <select
                                id="unit"
                                name="unit"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                            >
                                <option value="">Select unit</option>
                                <option value="pieces" {{ old('unit') === 'pieces' ? 'selected' : '' }}>pieces</option>
                                <option value="kg" {{ old('unit') === 'kg' ? 'selected' : '' }}>kg</option>
                                <option value="lbs" {{ old('unit') === 'lbs' ? 'selected' : '' }}>lbs</option>
                                <option value="containers" {{ old('unit') === 'containers' ? 'selected' : '' }}>containers</option>
                                <option value="boxes" {{ old('unit') === 'boxes' ? 'selected' : '' }}>boxes</option>
                                <option value="bags" {{ old('unit') === 'bags' ? 'selected' : '' }}>bags</option>
                                <option value="liters" {{ old('unit') === 'liters' ? 'selected' : '' }}>liters</option>
                            </select>
                            @error('unit')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Expiry Information -->
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Expiry Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="expiry_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Best Before Date *
                            </label>
                            <input
                                type="date"
                                id="expiry_date"
                                name="expiry_date"
                                required
                                min="{{ date('Y-m-d') }}"
                                value="{{ old('expiry_date') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                            />
                            @error('expiry_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="expiry_time" class="block text-sm font-medium text-gray-700 mb-2">
                                Best Before Time
                            </label>
                            <input
                                type="time"
                                id="expiry_time"
                                name="expiry_time"
                                value="{{ old('expiry_time') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                            />
                        </div>
                    </div>
                </div>

                <!-- Pickup Information -->
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Pickup Information</h2>
                    <div class="space-y-4">
                        <div>
                            <label for="pickup_location" class="block text-sm font-medium text-gray-700 mb-2">
                                Pickup Location *
                            </label>
                            <input
                                type="text"
                                id="pickup_location"
                                name="pickup_location"
                                required
                                value="{{ old('pickup_location') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                placeholder="e.g., Main Kitchen, Cold Storage Room, Back Entrance"
                            />
                            @error('pickup_location')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="special_instructions" class="block text-sm font-medium text-gray-700 mb-2">
                                Special Instructions
                            </label>
                            <textarea
                                id="special_instructions"
                                name="special_instructions"
                                rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                placeholder="Any special handling instructions, access codes, contact person, etc."
                            >{{ old('special_instructions') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Images ---->
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Food Images</h2>
                    <div class="space-y-4">
                        <div>
                            <label for="images" class="block text-sm font-medium text-gray-700 mb-2">
                                Upload Images (Optional)
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-green-400 transition-colors">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="images" class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-500">
                                            <span>Upload files</span>
                                            <input id="images" name="images[]" type="file" class="sr-only" multiple accept="image/*">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB each</p>
                                </div>
                            </div>
                            @error('images')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error('images.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Dietary Information -->
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Dietary Information</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @php
                            $dietaryOptions = ['Vegetarian', 'Vegan', 'Gluten-Free', 'Halal', 'Kosher', 'Nut-Free'];
                            $oldDietaryInfo = old('dietary_info', []);
                        @endphp
                        @foreach($dietaryOptions as $option)
                            <label class="flex items-center">
                                <input
                                    type="checkbox"
                                    name="dietary_info[]"
                                    value="{{ $option }}"
                                    {{ in_array($option, $oldDietaryInfo) ? 'checked' : '' }}
                                    class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                                />
                                <span class="ml-2 text-sm text-gray-700">{{ $option }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end space-x-4">
                    <a
                        href="{{ route('restaurant.dashboard') }}"
                        class="px-6 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                    >
                        Cancel
                    </a>
                    <button
                        type="submit"
                        class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 flex items-center"
                    >
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                        </svg>
                        Create Listing
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection