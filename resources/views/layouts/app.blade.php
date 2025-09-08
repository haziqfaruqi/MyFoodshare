<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'MyFoodshare - Reducing Food Waste, One Meal at a Time')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Firebase SDK -->
    @auth
    <script type="module">
        import { initializeApp } from 'https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js';
        import { getMessaging, getToken, onMessage } from 'https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging.js';

        const firebaseConfig = {
            apiKey: "{{ config('services.firebase.api_key') }}",
            projectId: "{{ config('services.firebase.project_id') }}",
        };

        let messaging;
        
        try {
            const app = initializeApp(firebaseConfig);
            messaging = getMessaging(app);

            // Register service worker
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register('/firebase-messaging-sw.js')
                    .then((registration) => {
                        console.log('Service Worker registered successfully');
                    })
                    .catch((error) => {
                        console.log('Service Worker registration failed');
                    });
            }

            // Request permission and get token
            async function requestNotificationPermission() {
                if (Notification.permission === 'granted') {
                    await getFCMToken();
                } else if (Notification.permission !== 'denied') {
                    const permission = await Notification.requestPermission();
                    if (permission === 'granted') {
                        await getFCMToken();
                    }
                }
            }

            async function getFCMToken() {
                try {
                    const token = await getToken(messaging, {
                        vapidKey: "{{ config('services.firebase.vapid_key') }}"
                    });
                    
                    if (token) {
                        // Send token to server
                        await fetch('/notifications/fcm-token', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ fcm_token: token })
                        });
                    }
                } catch (error) {
                    console.error('Error getting FCM token:', error);
                }
            }

            // Handle foreground messages
            onMessage(messaging, (payload) => {
                console.log('Message received in foreground:', payload);
                
                // Show browser notification if not in focus
                if (document.hidden) {
                    new Notification(payload.notification.title, {
                        body: payload.notification.body,
                        icon: '/favicon.ico',
                        badge: '/favicon.ico'
                    });
                } else {
                    // Show in-app notification
                    showInAppNotification(payload.notification);
                }
            });

            function showInAppNotification(notification) {
                // Create notification element
                const notificationEl = document.createElement('div');
                notificationEl.className = 'fixed top-4 right-4 bg-white border border-gray-200 rounded-lg shadow-lg p-4 max-w-sm z-50';
                notificationEl.innerHTML = `
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2L3 7v11c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V7l-7-5z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium text-gray-900">${notification.title}</p>
                            <p class="mt-1 text-sm text-gray-500">${notification.body}</p>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                `;

                document.body.appendChild(notificationEl);

                // Auto remove after 5 seconds
                setTimeout(() => {
                    if (notificationEl.parentElement) {
                        notificationEl.remove();
                    }
                }, 5000);
            }

            // Initialize on page load
            requestNotificationPermission();
            
        } catch (error) {
            console.error('Firebase initialization error:', error);
        }
    </script>
    @endauth
    
    @stack('head')
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        @yield('navbar')

        <!-- Page Content -->
        <main>
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative mx-4 mt-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative mx-4 mt-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
    
    @stack('scripts')
</body>
</html>