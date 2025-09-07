@extends('layouts.public')

@section('title', 'Contact Us - MyFoodshare')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-green-50 to-blue-50 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                Get in Touch
            </h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Have questions about MyFoodshare? Need support? Want to partner with us? 
                We'd love to hear from you.
            </p>
        </div>
    </section>

    <!-- Contact Form -->
    <section class="py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Send Us a Message</h2>
                    <p class="text-gray-600">
                        Fill out the form below and we'll get back to you as soon as possible
                    </p>
                </div>

                <form method="POST" action="{{ route('contact.submit') }}" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Full Name *
                            </label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                required
                                value="{{ old('name') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                placeholder="Enter your full name"
                            />
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email Address *
                            </label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                required
                                value="{{ old('email') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                placeholder="Enter your email address"
                            />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                                Inquiry Type
                            </label>
                            <select
                                id="type"
                                name="type"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                            >
                                <option value="general" {{ old('type') === 'general' ? 'selected' : '' }}>General Inquiry</option>
                                <option value="support" {{ old('type') === 'support' ? 'selected' : '' }}>Technical Support</option>
                                <option value="partnership" {{ old('type') === 'partnership' ? 'selected' : '' }}>Partnership</option>
                                <option value="feedback" {{ old('type') === 'feedback' ? 'selected' : '' }}>Feedback</option>
                                <option value="media" {{ old('type') === 'media' ? 'selected' : '' }}>Media Inquiry</option>
                            </select>
                        </div>

                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                                Subject *
                            </label>
                            <input
                                type="text"
                                id="subject"
                                name="subject"
                                required
                                value="{{ old('subject') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                placeholder="Brief subject line"
                            />
                        </div>
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                            Message *
                        </label>
                        <textarea
                            id="message"
                            name="message"
                            rows="6"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                            placeholder="Tell us how we can help you..."
                        >{{ old('message') }}</textarea>
                    </div>

                    <div class="text-center">
                        <button
                            type="submit"
                            class="bg-green-600 text-white px-8 py-3 rounded-md hover:bg-green-700 transition-colors flex items-center justify-center mx-auto"
                        >
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            Send Message
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection