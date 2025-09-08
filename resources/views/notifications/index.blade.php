@extends('layouts.app')

@section('title', 'Notifications - MyFoodshare')

@push('head')
<style>
.notification-item:hover {
    background-color: #f9fafb;
}
.notification-unread {
    background-color: #eff6ff;
}
</style>
@endpush

@section('navbar')
@if(auth()->user()->role === 'donor')
    @include('layouts.restaurant')
@elseif(auth()->user()->role === 'recipient')  
    @include('layouts.recipient')
@else
    @include('layouts.admin')
@endif
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Notifications</h1>
                <p class="text-gray-600 mt-1">Stay updated with the latest activities</p>
            </div>
            
            <div class="flex items-center space-x-3">
                <button onclick="markAllAsRead()" 
                        class="text-sm text-blue-600 hover:text-blue-800 font-medium"
                        id="markAllBtn">
                    Mark All as Read
                </button>
                
                <div class="flex items-center">
                    <span class="text-sm text-gray-500 mr-2">Unread:</span>
                    <span class="bg-red-100 text-red-800 text-xs font-medium px-2 py-1 rounded-full" 
                          id="unreadCount">{{ auth()->user()->unreadNotifications->count() }}</span>
                </div>
            </div>
        </div>

        <!-- Notifications List -->
        <div class="bg-white shadow rounded-lg">
            @if($notifications->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($notifications as $notification)
                        <div class="notification-item p-6 {{ $notification->read_at ? '' : 'notification-unread' }}" 
                             data-notification-id="{{ $notification->id }}">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start space-x-4 flex-1">
                                    <!-- Notification Icon -->
                                    <div class="flex-shrink-0">
                                        @php
                                            $type = $notification->data['type'] ?? 'default';
                                        @endphp
                                        
                                        @if($type === 'new_food_listing')
                                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                </svg>
                                            </div>
                                        @elseif($type === 'interest_expressed')
                                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                </svg>
                                            </div>
                                        @else
                                            <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5v-12"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Notification Content -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-semibold text-gray-900">
                                                {{ $notification->data['title'] ?? 'Notification' }}
                                            </p>
                                            @if(!$notification->read_at)
                                                <span class="w-2 h-2 bg-blue-600 rounded-full"></span>
                                            @endif
                                        </div>
                                        
                                        <p class="mt-1 text-sm text-gray-600">
                                            {{ $notification->data['message'] ?? 'No message available' }}
                                        </p>
                                        
                                        <!-- Additional Info -->
                                        <div class="mt-2 flex items-center space-x-4 text-xs text-gray-500">
                                            <span>{{ $notification->created_at->diffForHumans() }}</span>
                                            
                                            @if(isset($notification->data['food_name']))
                                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-gray-100 text-gray-700">
                                                    {{ $notification->data['food_name'] }}
                                                </span>
                                            @endif
                                            
                                            @if(isset($notification->data['distance']))
                                                <span class="text-blue-600">{{ $notification->data['distance'] }} away</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Actions -->
                                <div class="flex items-center space-x-2 ml-4">
                                    @if(isset($notification->data['action_url']))
                                        <a href="{{ route('notifications.show', $notification->id) }}" 
                                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            View
                                        </a>
                                    @endif
                                    
                                    @if(!$notification->read_at)
                                        <button onclick="markAsRead('{{ $notification->id }}')" 
                                                class="text-gray-400 hover:text-gray-600 text-sm">
                                            Mark Read
                                        </button>
                                    @endif
                                    
                                    <button onclick="deleteNotification('{{ $notification->id }}')" 
                                            class="text-red-400 hover:text-red-600 text-sm">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $notifications->links() }}
                </div>
            @else
                <div class="p-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5v-12"></path>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">No Notifications</h3>
                    <p class="mt-2 text-gray-500">You're all caught up! Check back later for updates.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
async function markAsRead(notificationId) {
    try {
        const response = await fetch(`/notifications/${notificationId}/read`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        });
        
        if (response.ok) {
            const item = document.querySelector(`[data-notification-id="${notificationId}"]`);
            item.classList.remove('notification-unread');
            item.querySelector('.w-2.h-2.bg-blue-600').style.display = 'none';
            updateUnreadCount();
        }
    } catch (error) {
        console.error('Error marking notification as read:', error);
    }
}

async function markAllAsRead() {
    try {
        const response = await fetch('/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        });
        
        if (response.ok) {
            document.querySelectorAll('.notification-unread').forEach(item => {
                item.classList.remove('notification-unread');
            });
            document.querySelectorAll('.w-2.h-2.bg-blue-600').forEach(dot => {
                dot.style.display = 'none';
            });
            updateUnreadCount();
        }
    } catch (error) {
        console.error('Error marking all notifications as read:', error);
    }
}

async function deleteNotification(notificationId) {
    if (!confirm('Are you sure you want to delete this notification?')) {
        return;
    }
    
    try {
        const response = await fetch(`/notifications/${notificationId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        });
        
        if (response.ok) {
            const item = document.querySelector(`[data-notification-id="${notificationId}"]`);
            item.remove();
            updateUnreadCount();
        }
    } catch (error) {
        console.error('Error deleting notification:', error);
    }
}

async function updateUnreadCount() {
    try {
        const response = await fetch('/notifications/unread-count');
        const data = await response.json();
        document.getElementById('unreadCount').textContent = data.count;
        
        if (data.count === 0) {
            document.getElementById('markAllBtn').style.display = 'none';
        }
    } catch (error) {
        console.error('Error updating unread count:', error);
    }
}
</script>
@endsection