// Firebase Messaging Service Worker
importScripts('https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging-compat.js');

// Initialize Firebase in service worker
firebase.initializeApp({
    apiKey: "your-api-key",
    projectId: "your-project-id",
    messagingSenderId: "your-sender-id",
    appId: "your-app-id"
});

const messaging = firebase.messaging();

// Handle background messages
messaging.onBackgroundMessage((payload) => {
    console.log('Received background message:', payload);

    const notificationTitle = payload.notification.title || 'MyFoodshare';
    const notificationOptions = {
        body: payload.notification.body || 'You have a new notification',
        icon: '/favicon.ico',
        badge: '/favicon.ico',
        tag: 'myfoodshare-notification',
        data: payload.data,
        actions: [
            {
                action: 'view',
                title: 'View',
                icon: '/favicon.ico'
            },
            {
                action: 'close',
                title: 'Close'
            }
        ]
    };

    return self.registration.showNotification(notificationTitle, notificationOptions);
});

// Handle notification clicks
self.addEventListener('notificationclick', (event) => {
    console.log('Notification clicked:', event);
    
    event.notification.close();

    if (event.action === 'close') {
        return;
    }

    // Handle notification click - open the app or specific page
    const urlToOpen = event.notification.data?.action_url || '/notifications';
    
    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true })
            .then((clientList) => {
                // Check if app is already open
                for (const client of clientList) {
                    if (client.url.includes(self.location.origin) && 'focus' in client) {
                        return client.focus().then(() => client.navigate(urlToOpen));
                    }
                }
                
                // Open new window if app is not open
                if (clients.openWindow) {
                    return clients.openWindow(urlToOpen);
                }
            })
    );
});