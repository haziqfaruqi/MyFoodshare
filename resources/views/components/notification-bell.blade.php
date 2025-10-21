<div class="relative" x-data="notificationBell" x-init="init()">
    <!-- Bell Icon Button -->
    <button
        @click="toggle()"
        class="relative p-2 text-gray-600 hover:text-green-600 hover:bg-green-50 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-green-500"
        aria-label="Notifications"
    >
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>

        <!-- Red Badge for Unread Count -->
        <span
            x-show="unreadCount > 0"
            x-text="unreadCount > 99 ? '99+' : unreadCount"
            class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-red-600 rounded-full min-w-[20px]"
            x-transition
        ></span>
    </button>

    <!-- Dropdown Panel -->
    <div
        x-show="open"
        @click.away="open = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-lg shadow-xl z-50 border border-gray-200 max-h-[32rem] flex flex-col"
        style="display: none;"
    >
        <!-- Header -->
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 bg-gray-50 rounded-t-lg">
            <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
            <button
                @click="markAllAsRead()"
                x-show="unreadCount > 0"
                class="text-xs text-green-600 hover:text-green-700 font-medium"
            >
                Mark all as read
            </button>
        </div>

        <!-- Notifications List -->
        <div class="overflow-y-auto flex-1">
            <template x-if="loading">
                <div class="px-4 py-8 text-center">
                    <div class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-green-600 border-r-transparent"></div>
                    <p class="mt-2 text-sm text-gray-500">Loading notifications...</p>
                </div>
            </template>

            <template x-if="!loading && notifications.length === 0">
                <div class="px-4 py-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="mt-2 text-sm text-gray-500">No notifications yet</p>
                </div>
            </template>

            <template x-if="!loading && notifications.length > 0">
                <div>
                    <template x-for="notification in notifications" :key="notification.id">
                        <a
                            :href="notification.data.action_url || '#'"
                            @click="markAsRead(notification.id)"
                            class="block px-4 py-3 hover:bg-gray-50 transition-colors border-b border-gray-100 last:border-b-0"
                            :class="{ 'bg-blue-50': !notification.read_at }"
                        >
                            <div class="flex items-start space-x-3">
                                <!-- Icon based on notification type -->
                                <div class="flex-shrink-0">
                                    <template x-if="notification.data.type === 'interest_expressed'">
                                        <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                            <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                                            </svg>
                                        </div>
                                    </template>
                                    <template x-if="notification.data.type === 'pickup_scheduled'">
                                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    </template>
                                    <template x-if="notification.data.type === 'pickup_completed'">
                                        <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
                                            <svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                    </template>
                                </div>

                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900" x-text="notification.data.title"></p>
                                    <p class="text-sm text-gray-600 mt-1" x-text="notification.data.message"></p>
                                    <p class="text-xs text-gray-400 mt-1" x-text="formatTime(notification.created_at)"></p>
                                </div>

                                <!-- Unread Indicator -->
                                <div x-show="!notification.read_at" class="flex-shrink-0">
                                    <span class="inline-block w-2 h-2 bg-blue-600 rounded-full"></span>
                                </div>
                            </div>
                        </a>
                    </template>
                </div>
            </template>
        </div>

        <!-- Footer -->
        <template x-if="notifications.length > 0">
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 rounded-b-lg">
                <a href="{{ route('notifications.index') }}" class="text-sm text-green-600 hover:text-green-700 font-medium">
                    View all notifications →
                </a>
            </div>
        </template>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    console.log('🎯 Alpine.js is ready, registering notification bell component');

    Alpine.data('notificationBell', () => ({
        open: false,
        loading: false,
        notifications: [],
        unreadCount: 0,

        init() {
            console.log('🔔 Notification Bell Component Initialized');
            this.fetchNotifications();
            this.fetchUnreadCount();
            this.listenForNotifications();
        },

        toggle() {
            this.open = !this.open;
            if (this.open) {
                this.fetchNotifications();
            }
        },

        async fetchNotifications() {
            this.loading = true;
            try {
                const response = await fetch('/notifications', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    credentials: 'same-origin'
                });
                const data = await response.json();
                this.notifications = (data.data || data || []).slice(0, 10); // Show last 10
            } catch (error) {
                console.error('Error fetching notifications:', error);
            } finally {
                this.loading = false;
            }
        },

        async fetchUnreadCount() {
            try {
                console.log('Fetching unread count from:', '/notifications/unread-count');
                const response = await fetch('/notifications/unread-count', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    credentials: 'same-origin'
                });
                console.log('Response status:', response.status, response.statusText);
                console.log('Response URL:', response.url);

                if (!response.ok) {
                    console.error('HTTP error!', response.status, response.statusText);
                    const text = await response.text();
                    console.error('Response body:', text);
                    return;
                }

                const data = await response.json();
                console.log('Unread count data:', data);
                this.unreadCount = data.count || 0;
            } catch (error) {
                console.error('Error fetching unread count:', error);
            }
        },

        async markAsRead(notificationId) {
            try {
                await fetch(`/notifications/${notificationId}/read`, {
                    method: 'PATCH',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    credentials: 'same-origin'
                });

                // Update local state
                const notification = this.notifications.find(n => n.id === notificationId);
                if (notification && !notification.read_at) {
                    notification.read_at = new Date().toISOString();
                    this.unreadCount = Math.max(0, this.unreadCount - 1);
                }
            } catch (error) {
                console.error('Error marking notification as read:', error);
            }
        },

        async markAllAsRead() {
            try {
                await fetch('/notifications/mark-all-read', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    credentials: 'same-origin'
                });

                // Update local state
                this.notifications.forEach(n => {
                    n.read_at = new Date().toISOString();
                });
                this.unreadCount = 0;
            } catch (error) {
                console.error('Error marking all as read:', error);
            }
        },

        listenForNotifications() {
            // Wait for Echo to be ready (it loads asynchronously)
            const setupListener = () => {
                if (typeof window.Echo !== 'undefined') {
                    console.log('🔔 Setting up notification listener for user: {{ auth()->id() }}');
                    console.log('📡 Channel:', `App.Models.User.{{ auth()->id() }}`);

                    const channel = window.Echo.private(`App.Models.User.{{ auth()->id() }}`);

                    channel.notification((notification) => {
                        console.log('✅ New notification received:', notification);

                        // Add to notifications list
                        this.notifications.unshift(notification);
                        if (this.notifications.length > 10) {
                            this.notifications.pop();
                        }

                        // Increment unread count
                        this.unreadCount++;

                        // Show browser notification if permitted
                        this.showBrowserNotification(notification);
                    });

                    // Listen for subscription success
                    channel.subscribed(() => {
                        console.log('✅ Successfully subscribed to notification channel');
                    });

                    // Listen for subscription errors
                    channel.error((error) => {
                        console.error('❌ Error subscribing to notification channel:', error);
                    });
                } else {
                    console.log('⏳ Waiting for Laravel Echo to initialize...');
                    // Retry after a short delay
                    setTimeout(setupListener, 100);
                }
            };

            setupListener();
        },

        showBrowserNotification(notification) {
            if ('Notification' in window && Notification.permission === 'granted') {
                new Notification(notification.title || 'New Notification', {
                    body: notification.message || '',
                    icon: '/images/logo.jpg',
                    tag: notification.id
                });
            } else if ('Notification' in window && Notification.permission !== 'denied') {
                Notification.requestPermission();
            }
        },

        formatTime(timestamp) {
            const date = new Date(timestamp);
            const now = new Date();
            const diffMs = now - date;
            const diffMins = Math.floor(diffMs / 60000);
            const diffHours = Math.floor(diffMs / 3600000);
            const diffDays = Math.floor(diffMs / 86400000);

            if (diffMins < 1) return 'Just now';
            if (diffMins < 60) return `${diffMins}m ago`;
            if (diffHours < 24) return `${diffHours}h ago`;
            if (diffDays < 7) return `${diffDays}d ago`;

            return date.toLocaleDateString();
        }
    }));
});
</script>
@endpush
