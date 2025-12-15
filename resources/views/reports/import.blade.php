<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Import Attendance Data') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Import Attendance from Excel</h3>
                    <p class="text-gray-600 mb-6">Upload an Excel file to import attendance data for a specific class.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Import Form -->
                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                            <form id="importForm" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-4">
                                    <label for="class_id" class="block text-sm font-medium text-gray-700">Select Class</label>
                                    <select id="class_id" name="class_id" required class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="">Select a class</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label for="file" class="block text-sm font-medium text-gray-700">Excel File</label>
                                    <input type="file" id="file" name="file" accept=".xlsx,.xls,.csv" required class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <p class="mt-1 text-xs text-gray-500">Only .xlsx, .xls, and .csv files are allowed.</p>
                                </div>

                                <button type="submit" id="importBtn" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                    Import Attendance
                                </button>
                            </form>
                        </div>

                        <!-- Instructions -->
                        <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
                            <h4 class="text-md font-medium text-blue-900 mb-2">Import Instructions</h4>
                            <ul class="text-sm text-blue-800 list-disc pl-5 space-y-1">
                                <li>Download the template below to see required format</li>
                                <li>Ensure student names and emails match existing records</li>
                                <li>Date format should be YYYY-MM-DD</li>
                                <li>Status should be one of: present, late, absent, excused, sick</li>
                                <li>Time format should be HH:MM</li>
                            </ul>

                            <div class="mt-4">
                                <a href="{{ route('reports.export.daily-excel') }}?class_id={{ $classes->first()->id ?? 1 }}&date={{ now()->format('Y-m-d') }}" 
                                   class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200">
                                    Download Sample Template
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Progress and Status -->
                    <div id="progressContainer" class="mt-6 hidden">
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                            <div class="flex items-center">
                                <div class="flex-1">
                                    <div class="h-2 bg-blue-200 rounded-full overflow-hidden">
                                        <div id="progressBar" class="h-full bg-blue-600 rounded-full" style="width: 0%"></div>
                                    </div>
                                </div>
                                <div id="progressText" class="ml-4 text-sm text-blue-800">Importing...</div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Message -->
                    <div id="statusMessage" class="mt-6 hidden p-4 rounded-md"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('importForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const importBtn = document.getElementById('importBtn');
            const progressContainer = document.getElementById('progressContainer');
            const statusMessage = document.getElementById('statusMessage');

            // Show progress
            progressContainer.classList.remove('hidden');
            document.getElementById('progressBar').style.width = '30%';
            document.getElementById('progressText').textContent = 'Uploading file...';

            // Disable button
            importBtn.disabled = true;
            importBtn.textContent = 'Importing...';

            // Send request
            fetch('/reports/import-excel', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                // Update progress
                document.getElementById('progressBar').style.width = '100%';
                document.getElementById('progressText').textContent = 'Complete';

                // Reset button
                importBtn.disabled = false;
                importBtn.textContent = 'Import Attendance';

                // Show status message
                statusMessage.classList.remove('hidden');
                if (data.success) {
                    statusMessage.className = 'mt-6 p-4 bg-green-100 text-green-800 rounded-md';
                    statusMessage.innerHTML = '<p>✓ ' + data.message + '</p>';
                } else {
                    statusMessage.className = 'mt-6 p-4 bg-red-100 text-red-800 rounded-md';
                    statusMessage.innerHTML = '<p>✗ Error: ' + data.message + '</p>';
                }

                // Hide progress after delay
                setTimeout(() => {
                    progressContainer.classList.add('hidden');
                }, 2000);

                // Hide message after 10 seconds
                setTimeout(() => {
                    statusMessage.classList.add('hidden');
                }, 10000);
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Reset button
                importBtn.disabled = false;
                importBtn.textContent = 'Import Attendance';

                // Show error message
                statusMessage.classList.remove('hidden');
                statusMessage.className = 'mt-6 p-4 bg-red-100 text-red-800 rounded-md';
                statusMessage.innerHTML = '<p>✗ Error importing data. Please try again.</p>';

                // Hide progress
                progressContainer.classList.add('hidden');
            });
        });
    </script>
</x-app-layout>