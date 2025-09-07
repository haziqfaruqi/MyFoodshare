@extends('layouts.public')

@section('title', 'About Us - MyFoodshare')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-green-50 to-blue-50 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                About MyFoodshare
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-8">
                We're on a mission to eliminate food waste while feeding communities in need. 
                Our platform connects restaurants with surplus food to local organizations and individuals who can benefit from it.
            </p>
            <div class="flex justify-center">
                <svg class="h-16 w-16 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
            </div>
        </div>
    </section>

    <!-- Mission Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Our Mission</h2>
                    <p class="text-lg text-gray-600 mb-6">
                        Every day, millions of pounds of perfectly good food go to waste while people in our communities go hungry. 
                        MyFoodshare bridges this gap by creating a seamless platform that connects surplus food from restaurants 
                        with those who need it most.
                    </p>
                    <p class="text-lg text-gray-600 mb-8">
                        We believe that technology can solve real-world problems, and we're committed to making food redistribution 
                        efficient, transparent, and impactful for everyone involved.
                    </p>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700">Reduce food waste by 85% in partner restaurants</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700">Provide nutritious meals to underserved communities</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700">Create positive environmental impact through waste reduction</span>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                        <svg class="h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="absolute -bottom-6 -left-6 bg-green-600 text-white p-6 rounded-lg shadow-lg">
                        <div class="text-2xl font-bold">15,000+</div>
                        <div class="text-green-100">Meals Redistributed</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->}
    <section class="py-20 bg-white">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Join Our Mission</h2>
            <p class="text-xl text-gray-600 mb-8">
                Whether you're a restaurant owner looking to reduce waste or an organization serving your community, 
                we'd love to have you as part of the MyFoodshare family.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a
                    href="{{ route('register') }}"
                    class="bg-green-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-700 transition-colors"
                >
                    Become a Partner
                </a>
                <a
                    href="{{ route('contact') }}"
                    class="border-2 border-green-600 text-green-600 px-8 py-3 rounded-lg font-semibold hover:bg-green-600 hover:text-white transition-colors"
                >
                    Get in Touch
                </a>
            </div>
        </div>
    </section>
</div>
@endsection