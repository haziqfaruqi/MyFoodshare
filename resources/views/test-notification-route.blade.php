<!DOCTYPE html>
<html>
<head>
    <title>Notification Route Test</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-4">Notification Route Test</h1>

        <div class="space-y-4">
            <div>
                <h2 class="font-semibold mb-2">Authentication Status:</h2>
                <pre class="bg-gray-100 p-3 rounded">{{ json_encode([
                    'authenticated' => auth()->check(),
                    'user_id' => auth()->id(),
                    'user_name' => auth()->user()->name ?? 'Not logged in'
                ], JSON_PRETTY_PRINT) }}</pre>
            </div>

            <div>
                <h2 class="font-semibold mb-2">Test Endpoints:</h2>
                <div class="space-y-2">
                    <button onclick="testEndpoint('/notifications')" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 w-full text-left">
                        Test GET /notifications
                    </button>
                    <button onclick="testEndpoint('/notifications/unread-count')" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 w-full text-left">
                        Test GET /notifications/unread-count
                    </button>
                    <button onclick="testRoute()" class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600 w-full text-left">
                        Test Laravel Route Helper
                    </button>
                </div>
            </div>

            <div>
                <h2 class="font-semibold mb-2">Response:</h2>
                <pre id="response" class="bg-gray-100 p-3 rounded min-h-[100px]">Click a button above to test...</pre>
            </div>

            <div>
                <h2 class="font-semibold mb-2">Route Information:</h2>
                <pre class="bg-gray-100 p-3 rounded">{{ json_encode([
                    'notifications.index' => route('notifications.index'),
                    'notifications.unread-count' => route('notifications.unread-count'),
                    'current_url' => url()->current(),
                    'app_url' => config('app.url')
                ], JSON_PRETTY_PRINT) }}</pre>
            </div>
        </div>
    </div>

    <script>
        async function testEndpoint(endpoint) {
            const response = document.getElementById('response');
            response.textContent = 'Loading...';

            try {
                const res = await fetch(endpoint, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    credentials: 'same-origin'
                });

                const data = await res.json();
                response.textContent = JSON.stringify({
                    status: res.status,
                    statusText: res.statusText,
                    data: data
                }, null, 2);
            } catch (error) {
                response.textContent = 'Error: ' + error.message;
            }
        }

        function testRoute() {
            const response = document.getElementById('response');
            response.textContent = JSON.stringify({
                'route() helper works': true,
                'notifications.unread-count': '{{ route("notifications.unread-count") }}'
            }, null, 2);
        }
    </script>
</body>
</html>
