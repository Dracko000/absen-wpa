<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cache Management') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Cache Management</h3>
                    <p class="text-gray-600 mb-6">Clear different types of caches to refresh the application data.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Clear All Cache -->
                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                            <h4 class="text-md font-medium text-gray-900 mb-2">Clear All Cache</h4>
                            <p class="text-sm text-gray-600 mb-4">Clears all caches including application cache, config, routes, and views.</p>
                            
                            <button id="clearAllCacheBtn" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Clear All Cache
                            </button>
                        </div>

                        <!-- Cache Information -->
                        <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
                            <h4 class="text-md font-medium text-blue-900 mb-2">Cache Information</h4>
                            <p class="text-sm text-blue-800">Current cache driver: {{ config('cache.default') }}</p>
                            <p class="text-sm text-blue-800">Cache is stored in: {{ config('cache.stores.' . config('cache.default') . '.driver') ?? 'default store' }}</p>
                        </div>
                    </div>

                    <!-- Status Message -->
                    <div id="statusMessage" class="mt-6 hidden p-4 rounded-md"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const clearAllCacheBtn = document.getElementById('clearAllCacheBtn');
            const statusMessage = document.getElementById('statusMessage');

            clearAllCacheBtn.addEventListener('click', function() {
                // Show loading state
                clearAllCacheBtn.disabled = true;
                clearAllCacheBtn.innerHTML = 'Clearing...';

                // Send request to clear cache
                fetch('/clear-cache', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Reset button
                    clearAllCacheBtn.disabled = false;
                    clearAllCacheBtn.innerHTML = 'Clear All Cache';

                    // Show status message
                    statusMessage.classList.remove('hidden');
                    if (data.success) {
                        statusMessage.className = 'mt-6 p-4 bg-green-100 text-green-800 rounded-md';
                        statusMessage.innerHTML = '<p>✓ ' + data.message + '</p>';
                    } else {
                        statusMessage.className = 'mt-6 p-4 bg-red-100 text-red-800 rounded-md';
                        statusMessage.innerHTML = '<p>✗ Error: ' + data.message + '</p>';
                    }

                    // Hide message after 5 seconds
                    setTimeout(() => {
                        statusMessage.classList.add('hidden');
                    }, 5000);
                })
                .catch(error => {
                    console.error('Error:', error);
                    
                    // Reset button
                    clearAllCacheBtn.disabled = false;
                    clearAllCacheBtn.innerHTML = 'Clear All Cache';

                    // Show error message
                    statusMessage.classList.remove('hidden');
                    statusMessage.className = 'mt-6 p-4 bg-red-100 text-red-800 rounded-md';
                    statusMessage.innerHTML = '<p>✗ Error clearing cache. Please try again.</p>';

                    // Hide message after 5 seconds
                    setTimeout(() => {
                        statusMessage.classList.add('hidden');
                    }, 5000);
                });
            });
        });
    </script>
</x-app-layout>