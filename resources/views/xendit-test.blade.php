<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xendit Connection Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Xendit Connection Test</h1>
            
            <div class="space-y-4">
                <!-- Test Connection -->
                <div class="border rounded-lg p-4">
                    <h2 class="text-lg font-semibold mb-2">1. Test Koneksi Dasar</h2>
                    <button onclick="testConnection()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Test Connection
                    </button>
                    <div id="connectionResult" class="mt-2 p-3 hidden rounded"></div>
                </div>

                <!-- Test Webhook -->
                <div class="border rounded-lg p-4">
                    <h2 class="text-lg font-semibold mb-2">2. Test Webhook URL</h2>
                    <button onclick="testWebhook()" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                        Test Webhook
                    </button>
                    <div id="webhookResult" class="mt-2 p-3 hidden rounded"></div>
                </div>

                <!-- Test Virtual Account -->
                <div class="border rounded-lg p-4">
                    <h2 class="text-lg font-semibold mb-2">3. Test Virtual Account Creation</h2>
                    <button onclick="testVA()" class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600">
                        Test VA Creation
                    </button>
                    <div id="vaResult" class="mt-2 p-3 hidden rounded"></div>
                </div>

                <!-- Environment Info -->
                <div class="border rounded-lg p-4 bg-gray-50">
                    <h2 class="text-lg font-semibold mb-2">Environment Information</h2>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <strong>APP_ENV:</strong> {{ config('app.env') }}
                        </div>
                        <div>
                            <strong>APP_DEBUG:</strong> {{ config('app.debug') ? 'Yes' : 'No' }}
                        </div>
                        <div>
                            <strong>Xendit Key:</strong> 
                            @if(config('xendit.secret_key'))
                                <span class="text-green-600">Configured</span>
                            @else
                                <span class="text-red-600">Not Configured</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function testConnection() {
            fetch('/xendit/test/connection')
                .then(response => response.json())
                .then(data => {
                    const resultDiv = document.getElementById('connectionResult');
                    resultDiv.className = 'mt-2 p-3 rounded ' + (data.success ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800');
                    resultDiv.innerHTML = `<pre>${JSON.stringify(data, null, 2)}</pre>`;
                    resultDiv.classList.remove('hidden');
                });
        }

        function testWebhook() {
            fetch('/xendit/test/webhook')
                .then(response => response.json())
                .then(data => {
                    const resultDiv = document.getElementById('webhookResult');
                    resultDiv.className = 'mt-2 p-3 rounded ' + (data.success ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800');
                    resultDiv.innerHTML = `<pre>${JSON.stringify(data, null, 2)}</pre>`;
                    resultDiv.classList.remove('hidden');
                });
        }

        function testVA() {
            fetch('/xendit/test/va')
                .then(response => response.json())
                .then(data => {
                    const resultDiv = document.getElementById('vaResult');
                    resultDiv.className = 'mt-2 p-3 rounded ' + (data.success ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800');
                    resultDiv.innerHTML = `<pre>${JSON.stringify(data, null, 2)}</pre>`;
                    resultDiv.classList.remove('hidden');
                });
        }
    </script>
</body>
</html>