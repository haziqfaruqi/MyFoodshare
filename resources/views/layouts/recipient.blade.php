@extends('layouts.app')

@section('navbar')
<nav class="bg-white shadow-lg sticky top-0 z-50 border-b-2 border-green-500">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="{{ route('recipient.browse.index') }}" class="flex items-center space-x-2">
                    <img src="{{ asset('images/logo.jpg') }}" alt="MyFoodshare" class="h-10 w-auto object-contain">
                    <div>
                        <div class="text-xs text-green-600 font-medium flex items-center">
                            <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Recipient Portal
                        </div>
                    </div>
                </a>
            </div>

            <!-- Mobile menu button -->
            <div class="lg:hidden">
                <button onclick="toggleMobileMenu()" class="text-gray-600 hover:text-green-600 focus:outline-none focus:text-green-600 p-2">
                    <svg id="mobileMenuIcon" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg id="mobileMenuCloseIcon" class="h-6 w-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden lg:flex items-center space-x-1">
                <a href="{{ route('recipient.dashboard') }}" class="flex items-center space-x-1 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('recipient.dashboard') ? 'bg-green-100 text-green-700' : 'text-gray-600 hover:text-green-600 hover:bg-green-50' }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m7 7l5 5l5-5"></path>
                    </svg>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('recipient.browse.index') }}" class="flex items-center space-x-1 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('recipient.browse.index', 'recipient.browse.show') ? 'bg-green-100 text-green-700' : 'text-gray-600 hover:text-green-600 hover:bg-green-50' }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <span>Browse Food</span>
                </a>
                <a href="{{ route('recipient.browse.map') }}" class="flex items-center space-x-1 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('recipient.browse.map') ? 'bg-green-100 text-green-700' : 'text-gray-600 hover:text-green-600 hover:bg-green-50' }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                    </svg>
                    <span>Map View</span>
                </a>
                <a href="{{ route('recipient.matches.index') }}" class="flex items-center space-x-1 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('recipient.matches.*') ? 'bg-green-100 text-green-700' : 'text-gray-600 hover:text-green-600 hover:bg-green-50' }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    <span>My Matches</span>
                </a>
            </div>

            <!-- User Menu -->
            <div class="hidden lg:flex items-center">
                <div class="relative">
                    <button onclick="toggleUserMenu()" class="flex items-center space-x-2 text-gray-700 hover:text-green-600 transition-colors px-3 py-2 rounded-md">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div class="text-left">
                            <div class="text-sm font-medium">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-gray-500">{{ auth()->user()->organization_name ?: 'Food Recipient' }}</div>
                        </div>
                    </button>
                    
                    <div id="userMenu" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg py-1 z-50 border">
                        <div class="px-4 py-2 border-b">
                            <div class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-gray-500">{{ auth()->user()->email }}</div>
                        </div>
                        <hr class="my-1">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Mobile Navigation Menu -->
        <div id="mobileMenu" class="lg:hidden hidden border-t border-gray-200">
            <div class="px-4 py-3 space-y-1 bg-white">
                <a href="{{ route('recipient.dashboard') }}" class="flex items-center space-x-2 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('recipient.dashboard') ? 'bg-green-100 text-green-700' : 'text-gray-600 hover:text-green-600 hover:bg-green-50' }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m7 7l5 5l5-5"></path>
                    </svg>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('recipient.browse.index') }}" class="flex items-center space-x-2 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('recipient.browse.index', 'recipient.browse.show') ? 'bg-green-100 text-green-700' : 'text-gray-600 hover:text-green-600 hover:bg-green-50' }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <span>Browse Food</span>
                </a>
                <a href="{{ route('recipient.browse.map') }}" class="flex items-center space-x-2 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('recipient.browse.map') ? 'bg-green-100 text-green-700' : 'text-gray-600 hover:text-green-600 hover:bg-green-50' }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                    </svg>
                    <span>Map View</span>
                </a>
                <a href="{{ route('recipient.matches.index') }}" class="flex items-center space-x-2 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('recipient.matches.*') ? 'bg-green-100 text-green-700' : 'text-gray-600 hover:text-green-600 hover:bg-green-50' }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    <span>My Matches</span>
                </a>
                
                <!-- Mobile User Menu -->
                <div class="border-t border-gray-200 mt-4 pt-4">
                    <div class="flex items-center px-3 py-2 mb-2">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                            <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-gray-500">{{ auth()->user()->organization_name ?: 'Food Recipient' }}</div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
function toggleUserMenu() {
    const menu = document.getElementById('userMenu');
    menu.classList.toggle('hidden');
}

function toggleMobileMenu() {
    const menu = document.getElementById('mobileMenu');
    const menuIcon = document.getElementById('mobileMenuIcon');
    const closeIcon = document.getElementById('mobileMenuCloseIcon');
    
    menu.classList.toggle('hidden');
    menuIcon.classList.toggle('hidden');
    closeIcon.classList.toggle('hidden');
}

document.addEventListener('click', function(event) {
    const userMenu = document.getElementById('userMenu');
    const mobileMenu = document.getElementById('mobileMenu');
    const button = event.target.closest('button');
    
    // Close user menu if clicked outside
    if (!button || !button.onclick || !button.onclick.toString().includes('toggleUserMenu')) {
        userMenu?.classList.add('hidden');
    }
    
    // Close mobile menu if clicked outside or on a link
    if (event.target.tagName === 'A' && mobileMenu && !mobileMenu.classList.contains('hidden')) {
        mobileMenu.classList.add('hidden');
        document.getElementById('mobileMenuIcon')?.classList.remove('hidden');
        document.getElementById('mobileMenuCloseIcon')?.classList.add('hidden');
    }
});
</script>
@endsection