@extends('layouts.app')

@section('navbar')
<nav class="bg-white shadow-lg sticky top-0 z-50 border-b-2 border-green-500">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="{{ route('restaurant.dashboard') }}" class="flex items-center space-x-2">
                    <img src="{{ asset('images/logo.jpg') }}" alt="MyFoodshare" class="h-10 w-auto object-contain">
                    <div>
                        <div class="text-xs text-green-600 font-medium">Restaurant Portal</div>
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
                <a href="{{ route('restaurant.dashboard') }}" class="flex items-center space-x-1 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('restaurant.dashboard') ? 'bg-green-100 text-green-700' : 'text-gray-600 hover:text-green-600 hover:bg-green-50' }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('restaurant.listings.create') }}" class="flex items-center space-x-1 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('restaurant.listings.create') ? 'bg-green-100 text-green-700' : 'text-gray-600 hover:text-green-600 hover:bg-green-50' }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span>Create Listing</span>
                </a>
                <a href="{{ route('restaurant.listings.index') }}" class="flex items-center space-x-1 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('restaurant.listings.*') && !request()->routeIs('restaurant.listings.create') ? 'bg-green-100 text-green-700' : 'text-gray-600 hover:text-green-600 hover:bg-green-50' }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <span>Manage Listings</span>
                </a>
                <a href="{{ route('restaurant.matches.index') }}" class="flex items-center space-x-1 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('restaurant.matches.*') ? 'bg-green-100 text-green-700' : 'text-gray-600 hover:text-green-600 hover:bg-green-50' }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Manage Requests</span>
                </a>
                <a href="{{ route('restaurant.profile.show') }}" class="flex items-center space-x-1 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('restaurant.profile.*') ? 'bg-green-100 text-green-700' : 'text-gray-600 hover:text-green-600 hover:bg-green-50' }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span>Profile</span>
                </a>
            </div>

            <!-- User Menu -->
            <div class="hidden lg:flex items-center">
                <div class="relative">
                    <button onclick="toggleUserMenu()" class="flex items-center space-x-2 text-gray-700 hover:text-green-600 transition-colors px-3 py-2 rounded-md">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <div class="text-left">
                            <div class="text-sm font-medium">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-gray-500">{{ auth()->user()->restaurant_name }}</div>
                        </div>
                    </button>
                    
                    <div id="userMenu" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg py-1 z-50 border">
                        <div class="px-4 py-2 border-b">
                            <div class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-gray-500">{{ auth()->user()->email }}</div>
                        </div>
                        <a href="{{ route('restaurant.profile.show') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Profile Settings
                        </a>
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
                <a href="{{ route('restaurant.dashboard') }}" class="flex items-center space-x-2 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('restaurant.dashboard') ? 'bg-green-100 text-green-700' : 'text-gray-600 hover:text-green-600 hover:bg-green-50' }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('restaurant.listings.create') }}" class="flex items-center space-x-2 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('restaurant.listings.create') ? 'bg-green-100 text-green-700' : 'text-gray-600 hover:text-green-600 hover:bg-green-50' }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span>Create Listing</span>
                </a>
                <a href="{{ route('restaurant.listings.index') }}" class="flex items-center space-x-2 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('restaurant.listings.*') && !request()->routeIs('restaurant.listings.create') ? 'bg-green-100 text-green-700' : 'text-gray-600 hover:text-green-600 hover:bg-green-50' }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <span>Manage Listings</span>
                </a>
                <a href="{{ route('restaurant.matches.index') }}" class="flex items-center space-x-2 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('restaurant.matches.*') ? 'bg-green-100 text-green-700' : 'text-gray-600 hover:text-green-600 hover:bg-green-50' }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Manage Requests</span>
                </a>
                <a href="{{ route('restaurant.profile.show') }}" class="flex items-center space-x-2 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('restaurant.profile.*') ? 'bg-green-100 text-green-700' : 'text-gray-600 hover:text-green-600 hover:bg-green-50' }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span>Profile</span>
                </a>
                
                <!-- Mobile User Menu -->
                <div class="border-t border-gray-200 mt-4 pt-4">
                    <div class="flex items-center px-3 py-2 mb-2">
                        <svg class="h-5 w-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-gray-500">{{ auth()->user()->restaurant_name }}</div>
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