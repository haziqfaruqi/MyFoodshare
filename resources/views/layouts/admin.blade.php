@extends('layouts.app')

@section('navbar')
<nav class="bg-white shadow-lg sticky top-0 z-50 border-b-2 border-blue-500">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    <div>
                        <span class="text-xl font-bold text-gray-800">MyFoodshare</span>
                        <div class="text-xs text-blue-600 font-medium flex items-center">
                            <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            Admin Portal
                        </div>
                    </div>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden lg:flex items-center space-x-1">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-1 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50' }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <!-- Approvals Dropdown -->
                <div class="relative">
                    <button onclick="toggleDropdown('approvalsMenu')" class="flex items-center space-x-1 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('admin.pending-approvals.*', 'admin.listing-approvals.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50' }}">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Approvals</span>
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="approvalsMenu" class="hidden absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border">
                        <a href="{{ route('admin.pending-approvals.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 {{ request()->routeIs('admin.pending-approvals.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                            <svg class="h-4 w-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            User Approvals
                        </a>
                        <a href="{{ route('admin.listing-approvals.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 {{ request()->routeIs('admin.listing-approvals.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                            <svg class="h-4 w-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h6a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Listing Approvals
                        </a>
                    </div>
                </div>

                <!-- Management Dropdown -->
                <div class="relative">
                    <button onclick="toggleDropdown('managementMenu')" class="flex items-center space-x-1 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('admin.active-listings.*', 'admin.users.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50' }}">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>Management</span>
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="managementMenu" class="hidden absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border">
                        <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 {{ request()->routeIs('admin.users.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                            <svg class="h-4 w-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            User Management
                        </a>
                        <a href="{{ route('admin.active-listings.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 {{ request()->routeIs('admin.active-listings.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                            <svg class="h-4 w-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            Active Listings
                        </a>
                    </div>
                </div>

                <!-- Monitoring -->
                <a href="{{ route('admin.pickup-verifications.index') }}" class="flex items-center space-x-1 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('admin.pickup-verifications.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50' }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    <span>Monitoring</span>
                </a>

                <!-- Analytics -->
                <a href="{{ route('admin.analytics.index') }}" class="flex items-center space-x-1 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('admin.analytics.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50' }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span>Analytics</span>
                </a>
            </div>

            <!-- User Menu -->
            <div class="hidden md:flex items-center">
                <div class="relative">
                    <button onclick="toggleUserMenu()" class="flex items-center space-x-2 text-gray-700 hover:text-blue-600 transition-colors px-3 py-2 rounded-md">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <div class="text-left">
                            <div class="text-sm font-medium">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-gray-500">System Administrator</div>
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
    </div>
</nav>

<script>
function toggleUserMenu() {
    const menu = document.getElementById('userMenu');
    menu.classList.toggle('hidden');
}

function toggleDropdown(menuId) {
    // Close other dropdowns first
    const dropdowns = ['approvalsMenu', 'managementMenu'];
    dropdowns.forEach(id => {
        if (id !== menuId) {
            document.getElementById(id).classList.add('hidden');
        }
    });
    
    // Toggle the requested dropdown
    const menu = document.getElementById(menuId);
    menu.classList.toggle('hidden');
}

document.addEventListener('click', function(event) {
    const userMenu = document.getElementById('userMenu');
    const approvalsMenu = document.getElementById('approvalsMenu');
    const managementMenu = document.getElementById('managementMenu');
    
    // Check if click was inside any dropdown or its button
    const isInsideDropdown = event.target.closest('.relative');
    
    if (!isInsideDropdown) {
        userMenu.classList.add('hidden');
        approvalsMenu.classList.add('hidden');
        managementMenu.classList.add('hidden');
    }
});
</script>
@endsection