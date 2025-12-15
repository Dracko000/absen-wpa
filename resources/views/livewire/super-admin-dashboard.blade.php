<div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Students</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $totalStudents }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Teachers</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $totalTeachers }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Classes</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $totalClasses }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Today's Attendance</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $attendanceToday }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Export Section and Classes List -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Quick Export Section -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Export Attendance Reports</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Download system-wide attendance reports in Excel format</p>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <div class="space-y-4">
                            @if($classes->count() > 0)
                                <form method="POST" action="{{ route('reports.export.daily-excel') }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="class_id" value="{{ $classes->first()->id }}">
                                    <input type="hidden" name="date" value="{{ now()->format('Y-m-d') }}">
                                    <button type="submit" class="bg-teal-500 hover:bg-teal-600 text-black font-medium py-2 px-4 rounded-md text-sm flex items-center">
                                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Daily Report
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('reports.export.weekly-excel') }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="class_id" value="{{ $classes->first()->id }}">
                                    <input type="hidden" name="week_start_date" value="{{ now()->startOfWeek()->format('Y-m-d') }}">
                                    <button type="submit" class="bg-teal-500 hover:bg-teal-600 text-black font-medium py-2 px-4 rounded-md text-sm flex items-center">
                                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Weekly Report
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('reports.export.monthly-excel') }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="class_id" value="{{ $classes->first()->id }}">
                                    <input type="hidden" name="month" value="{{ now()->format('Y-m') }}">
                                    <button type="submit" class="bg-teal-500 hover:bg-teal-600 text-black font-medium py-2 px-4 rounded-md text-sm flex items-center">
                                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Monthly Report
                                    </button>
                                </form>
                            @else
                                <p class="text-sm text-gray-500">No classes found. Please create a class first to export reports.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Classes List -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Classes</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">List of all classes in the system</p>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <ul class="divide-y divide-gray-200">
                            @forelse($classes as $class)
                                <li class="py-4">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $class->name }}</p>
                                            <p class="text-sm text-gray-500 truncate">Teacher: {{ $class->teacher->name }}</p>
                                        </div>
                                        <div>
                                            <button onclick="toggleClassStudents({{ $class->id }})" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-full shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                                                View Students
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Student List for this Class - Initially Hidden -->
                                    <div id="students-list-{{ $class->id }}" class="mt-4 p-4 bg-gray-50 rounded-lg hidden">
                                        <h4 class="font-medium text-gray-700 mb-2">Students in {{ $class->name }}:</h4>
                                        <ul class="divide-y divide-gray-200">
                                            @forelse($classMembers[$class->id] ?? collect([]) as $student)
                                                <li class="py-2 flex justify-between">
                                                    <span>{{ $student->name }}</span>
                                                    <span class="text-sm text-gray-500">{{ $student->email }}</span>
                                                </li>
                                            @empty
                                                <li class="py-2 text-gray-500">No students assigned to this class</li>
                                            @endforelse
                                        </ul>
                                    </div>
                                </li>
                            @empty
                                <li class="py-4">
                                    <p class="text-center text-gray-500">No classes found</p>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <!-- Recent Admin Attendance -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Admin Attendance</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Latest admin and superadmin attendance records</p>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flow-root">
                            <ul class="divide-y divide-gray-200">
                                @forelse($recentAdminAttendances as $attendance)
                                    <li class="py-4">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-shrink-0">
                                                <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="text-sm font-medium text-gray-900 truncate">{{ $attendance->user->name }}</p>
                                                <p class="text-sm text-gray-500 truncate">{{ $attendance->user->email }} ({{ $attendance->user->role }})</p>
                                            </div>
                                            <div>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    {{ ($attendance->status === 'present') ? 'bg-green-100 text-green-800' :
                                                       (($attendance->status === 'late') ? 'bg-yellow-100 text-yellow-800' :
                                                       (($attendance->status === 'absent') ? 'bg-red-100 text-red-800' :
                                                       'bg-blue-100 text-blue-800')) }}">
                                                    {{ ucfirst($attendance->status) }}
                                                </span>
                                            </div>
                                        </div>
                                    </li>
                                @empty
                                    <li class="py-4">
                                        <p class="text-center text-gray-500">No admin attendance records found</p>
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Recent Attendance -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Attendance</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Latest attendance records for all users</p>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flow-root">
                            <ul class="divide-y divide-gray-200">
                                @forelse($recentAttendances as $attendance)
                                    <li class="py-4">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-shrink-0">
                                                <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                </svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="text-sm font-medium text-gray-900 truncate">{{ $attendance->user->name }}</p>
                                                <p class="text-sm text-gray-500 truncate">{{ $attendance->schedule->classModel->name }} - {{ $attendance->schedule->subject->name }}</p>
                                            </div>
                                            <div>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    {{ ($attendance->status === 'present') ? 'bg-green-100 text-green-800' :
                                                       (($attendance->status === 'late') ? 'bg-yellow-100 text-yellow-800' :
                                                       (($attendance->status === 'absent') ? 'bg-red-100 text-red-800' :
                                                       'bg-blue-100 text-blue-800')) }}">
                                                    {{ ucfirst($attendance->status) }}
                                                </span>
                                            </div>
                                        </div>
                                    </li>
                                @empty
                                    <li class="py-4">
                                        <p class="text-center text-gray-500">No attendance records found</p>
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- QR Code Scanner for Admin Verification -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mt-6" id="admin-qr-scanner">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Admin QR Code Scanner</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Scan admin QR codes to verify admin identity and information</p>
                </div>

                <div class="p-6">
                    <div class="flex flex-col sm:flex-row sm:space-x-4 space-y-4 sm:space-y-0">
                        <button id="startAdminScannerBtn" class="bg-blue-500 hover:bg-blue-700 text-black font-bold py-3 px-4 rounded-lg flex-1 text-sm sm:text-base">
                            Scan Admin QR Code
                        </button>
                        <button id="adminUploadQRBtn" class="bg-green-500 hover:bg-green-700 text-black font-bold py-3 px-4 rounded-lg flex-1 text-sm sm:text-base">
                            Upload QR from Device
                        </button>
                        <button id="adminManualInputBtn" class="bg-gray-500 hover:bg-gray-700 text-black font-bold py-3 px-4 rounded-lg flex-1 text-sm sm:text-base">
                            Manual QR Input
                        </button>
                    </div>

                    <div class="mb-6 mt-6" id="adminUploadContainer" style="display:none;">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Upload Admin QR Code Image</h3>
                        <div class="flex flex-col sm:flex-row sm:space-x-4 space-y-2 sm:space-y-0">
                            <input type="file" id="adminQrImageUpload" accept="image/*" class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:rounded-r-none sm:rounded-l-md">
                            <button id="adminProcessUploadBtn" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md sm:rounded-l-none sm:rounded-r-md mt-2 sm:mt-0">
                                Process
                            </button>
                        </div>
                    </div>

                    <div class="mb-6 mt-6" id="adminScannerContainer" style="display:none;">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Admin QR Camera Scanner</h3>
                        <div class="flex flex-col items-center">
                            <video id="adminVideo" width="100%" max-width="300" height="200" class="max-w-full h-auto" style="border: 1px solid #ccc;"></video>
                            <canvas id="adminCanvas" style="display: none;"></canvas>
                            <button id="adminCaptureBtn" class="mt-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg" style="display: none;">Capture</button>
                            <p id="adminScannerStatus" class="mt-2 text-sm text-gray-600">Point camera at admin QR code</p>
                        </div>
                    </div>

                    <div class="mb-6 mt-6" id="adminManualInputContainer" style="display:none;">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Manual Admin QR Code Input</h3>
                        <div class="flex flex-col sm:flex-row sm:space-x-0 space-y-2 sm:space-y-0">
                            <input type="text" id="adminQrInput" placeholder="Enter admin QR code data" class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:rounded-r-none sm:rounded-l-md">
                            <button id="adminSubmitQRBtn" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg sm:rounded-l-none sm:rounded-r-md mt-2 sm:mt-0">
                                Process
                            </button>
                        </div>
                    </div>

                    <div class="mb-6 mt-6" id="adminResultContainer" style="display:none;">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Admin QR Scan Result</h3>
                        <div id="adminResult" class="bg-gray-100 p-4 rounded-md"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Include jsQR for Admin QR code scanning by loading it dynamically
        if (typeof jsQR === 'undefined') {
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js';
            document.head.appendChild(script);

            // Wait for the script to load before running the main code
            script.onload = function() {
                initializeAdminScanner();
            };
        } else {
            // jsQR is already loaded
            initializeAdminScanner();
        }

        function initializeAdminScanner() {
            // Admin QR Scanner Elements
            const startAdminScannerBtn = document.getElementById('startAdminScannerBtn');
            const adminUploadQRBtn = document.getElementById('adminUploadQRBtn');
            const adminManualInputBtn = document.getElementById('adminManualInputBtn');
            const adminScannerContainer = document.getElementById('adminScannerContainer');
            const adminUploadContainer = document.getElementById('adminUploadContainer');
            const adminManualInputContainer = document.getElementById('adminManualInputContainer');
            const adminCaptureBtn = document.getElementById('adminCaptureBtn');
            const adminVideo = document.getElementById('adminVideo');
            const adminCanvas = document.getElementById('adminCanvas');
            const adminQrImageUpload = document.getElementById('adminQrImageUpload');
            const adminProcessUploadBtn = document.getElementById('adminProcessUploadBtn');
            const adminQrInput = document.getElementById('adminQrInput');
            const adminSubmitQRBtn = document.getElementById('adminSubmitQRBtn');
            const adminResultContainer = document.getElementById('adminResultContainer');
            const adminResultDiv = document.getElementById('adminResult');
            const adminScannerStatus = document.getElementById('adminScannerStatus');

            // Show manual input form for admin QR
            adminManualInputBtn.addEventListener('click', function() {
                adminManualInputContainer.style.display = 'block';
                adminScannerContainer.style.display = 'none';
                adminUploadContainer.style.display = 'none';
            });

            // Show upload form for admin QR
            adminUploadQRBtn.addEventListener('click', function() {
                adminScannerContainer.style.display = 'none';
                adminManualInputContainer.style.display = 'none';
                adminUploadContainer.style.display = 'block';
            });

            // Process uploaded admin QR image
            adminProcessUploadBtn.addEventListener('click', function() {
                const file = adminQrImageUpload.files[0];
                if (!file) {
                    adminResultDiv.innerHTML = '<p class="text-red-600">Please select a QR code image file</p>';
                    adminResultContainer.style.display = 'block';
                    return;
                }

                // Check if the file is an image
                if (!file.type.match('image.*')) {
                    adminResultDiv.innerHTML = '<p class="text-red-600">Please select an image file (JPEG, PNG, etc.)</p>';
                    adminResultContainer.style.display = 'block';
                    return;
                }

                // Show loading indicator
                adminResultDiv.innerHTML = '<p class="text-blue-600">Processing image, please wait...</p>';
                adminResultContainer.style.display = 'block';

                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = new Image();
                    img.onload = function() {
                        // Create a canvas to draw the image and scan for QR codes
                        const canvas = document.createElement('canvas');
                        const ctx = canvas.getContext('2d');

                        // Calculate optimal dimensions to speed up processing
                        const maxWidth = 800;
                        const maxHeight = 600;
                        let { width, height } = img;

                        // Calculate aspect ratio to maintain image proportions
                        if (width > maxWidth) {
                            height = (height * maxWidth) / width;
                            width = maxWidth;
                        }
                        if (height > maxHeight) {
                            width = (width * maxHeight) / height;
                            height = maxHeight;
                        }

                        canvas.width = width;
                        canvas.height = height;
                        ctx.drawImage(img, 0, 0, width, height);

                        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                        const code = jsQR(imageData.data, imageData.width, imageData.height);

                        if (code) {
                            // Check if code.data is not empty before processing
                            if (code.data && code.data.trim() !== '') {
                                adminResultDiv.innerHTML = '<p>Scanned Admin QR: ' + code.data + '</p>';
                                console.log('Uploaded Admin QR data:', code.data); // Debug log
                                processAdminScan(code.data);
                            } else {
                                adminResultDiv.innerHTML = '<p class="text-orange-600">QR detected in image but data is empty. Please try another image with a valid admin QR code.</p>';
                            }
                        } else {
                            adminResultDiv.innerHTML = '<p class="text-red-600">No QR code detected in the uploaded image. Please try another image.</p>';
                        }
                    };
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            });

            // Initialize admin camera scanner
            startAdminScannerBtn.addEventListener('click', function() {
                adminManualInputContainer.style.display = 'none';
                adminUploadContainer.style.display = 'none';
                adminScannerContainer.style.display = 'block';
                initAdminCameraScanner();
            });

            // Submit manually entered admin QR code
            adminSubmitQRBtn.addEventListener('click', function() {
                const qrData = adminQrInput.value.trim();
                if (!qrData) {
                    adminResultDiv.innerHTML = '<p class="text-red-600">Please enter QR code data</p>';
                    adminResultContainer.style.display = 'block';
                    return;
                }

                processAdminScan(qrData);
            });

            // Process admin QR code when captured
            adminCaptureBtn.addEventListener('click', function() {
                const videoWidth = adminVideo.videoWidth;
                const videoHeight = adminVideo.videoHeight;

                // Use optimized dimensions for capture
                const maxWidth = 800;
                const maxHeight = 600;
                let width = videoWidth;
                let height = videoHeight;

                // Calculate aspect ratio to maintain image proportions
                if (width > maxWidth) {
                    height = (height * maxWidth) / width;
                    width = maxWidth;
                }
                if (height > maxHeight) {
                    width = (width * maxHeight) / height;
                    height = maxHeight;
                }

                adminCanvas.width = width;
                adminCanvas.height = height;

                const context = adminCanvas.getContext('2d');
                context.drawImage(adminVideo, 0, 0, adminCanvas.width, adminCanvas.height);

                const imageData = context.getImageData(0, 0, adminCanvas.width, adminCanvas.height);
                const code = jsQR(imageData.data, imageData.width, imageData.height);

                if (code) {
                    console.log('Raw Admin QR code data:', code.data); // Debug log
                    // Check if code.data is not empty before processing
                    if (code.data && code.data.trim() !== '') {
                        adminResultDiv.innerHTML = '<p>Scanned Admin QR: ' + code.data + '</p>';
                        adminResultContainer.style.display = 'block';
                        processAdminScan(code.data);
                    } else {
                        adminResultDiv.innerHTML = '<p class="text-orange-600">QR detected but data is empty. Please try again with a valid admin QR code.</p>';
                        adminResultContainer.style.display = 'block';
                    }
                } else {
                    adminResultDiv.innerHTML = '<p class="text-red-600">No QR code detected. Please try again.</p>';
                    adminResultContainer.style.display = 'block';
                }
            });

            function initAdminCameraScanner() {
                if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                    navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
                        .then(function(stream) {
                            adminVideo.srcObject = stream;
                            adminVideo.setAttribute("playsinline", true); // required to tell iOS safari we don't want fullscreen
                            adminVideo.play();
                            adminScannerStatus.textContent = 'Camera active - Point at admin QR code';

                            // Start QR code detection
                            requestAnimationFrame(adminTick);
                        })
                        .catch(function(err) {
                            console.error("Error accessing camera: ", err);
                            adminResultDiv.innerHTML = '<p class="text-red-600">Error accessing camera: ' + err.message + '</p>';
                            adminResultContainer.style.display = 'block';
                        });
                } else {
                    adminResultDiv.innerHTML = '<p class="text-red-600">Camera not supported in this browser</p>';
                    adminResultContainer.style.display = 'block';
                }
            }

            function adminTick() {
                if (adminVideo.readyState === adminVideo.HAVE_ENOUGH_DATA) {
                    // Use smaller canvas dimensions for faster processing
                    const videoWidth = adminVideo.videoWidth;
                    const videoHeight = adminVideo.videoHeight;

                    // Only resize canvas if it's significantly different from video dimensions
                    if (adminCanvas.width !== videoWidth || adminCanvas.height !== videoHeight) {
                        // Calculate optimal dimensions to speed up processing
                        const maxWidth = 640;  // Smaller than before for faster processing
                        const maxHeight = 480;
                        let width = videoWidth;
                        let height = videoHeight;

                        // Calculate aspect ratio to maintain image proportions
                        if (width > maxWidth) {
                            height = (height * maxWidth) / width;
                            width = maxWidth;
                        }
                        if (height > maxHeight) {
                            width = (width * maxHeight) / height;
                            height = maxHeight;
                        }

                        adminCanvas.width = width;
                        adminCanvas.height = height;
                    }

                    const context = adminCanvas.getContext('2d');
                    context.drawImage(adminVideo, 0, 0, adminCanvas.width, adminCanvas.height);
                    const imageData = context.getImageData(0, 0, adminCanvas.width, adminCanvas.height);
                    const code = jsQR(imageData.data, imageData.width, imageData.height);

                    if (code) {
                        // Highlight the QR code area
                        context.lineWidth = 2;
                        context.strokeStyle = "#00FF00";
                        // Adjust highlight position based on scaled coordinates
                        const scaleX = adminCanvas.width / videoWidth;
                        const scaleY = adminCanvas.height / videoHeight;
                        context.strokeRect(
                            code.location.topLeftCorner.x * scaleX,
                            code.location.topLeftCorner.y * scaleY,
                            (code.location.topRightCorner.x - code.location.topLeftCorner.x) * scaleX,
                            (code.location.bottomRightCorner.y - code.location.topLeftCorner.y) * scaleY
                        );

                        console.log('Raw Admin QR code data:', code.data); // Debug log
                        // Check if code.data is not empty before processing
                        if (code.data && code.data.trim() !== '') {
                            // Display captured code
                            adminResultDiv.innerHTML = '<p>Scanned Admin QR: ' + code.data + '</p>';
                            adminResultContainer.style.display = 'block';
                            adminCaptureBtn.style.display = 'block';

                            console.log('Detected Admin QR data:', code.data); // Debug log

                            // Process the QR code immediately
                            processAdminScan(code.data);
                        } else {
                            adminResultDiv.innerHTML = '<p class="text-orange-600">QR detected but data is empty. Please try again with a valid admin QR code.</p>';
                            adminResultContainer.style.display = 'block';
                            adminCaptureBtn.style.display = 'none';
                        }
                    } else {
                        adminCaptureBtn.style.display = 'none';
                    }
                }
                requestAnimationFrame(adminTick);
            }

            function processAdminScan(qrData) {
                // Only proceed if qrData is not empty
                if (!qrData || qrData.trim() === '') {
                    adminResultDiv.innerHTML = '<p class="text-red-600">Error: No QR code data detected. Please make sure the admin QR code is properly positioned.</p>';
                    adminResultContainer.style.display = 'block';
                    return;
                }

                // Show validation indicator
                adminResultDiv.innerHTML = '<p class="text-blue-600">Validating QR code data...</p>';
                adminResultContainer.style.display = 'block';

                // First, validate the QR code using the debug endpoint
                fetch('/qr-code/debug', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        qr_data: qrData
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.decoded_data) {
                        const decodedData = data.decoded_data;
                        // Check if it's an admin QR code (based on type field in QR data)
                        // The QR code service sets type as the user's role (admin, superadmin, user, etc.)
                        if (decodedData.id && decodedData.type &&
                            (decodedData.type === 'admin' || decodedData.type === 'superadmin')) {
                            // Valid admin QR code - now record attendance
                            recordAdminAttendance(qrData, decodedData);
                        } else if (decodedData.id && decodedData.type && decodedData.type === 'user') {
                            // Valid student/user QR code
                            adminResultDiv.innerHTML =
                                '<div class="text-blue-600">' +
                                    '<p class="font-bold">Valid Student QR Code Scanned (Not Admin)</p>' +
                                    '<p><strong>Name:</strong> ' + decodedData.name + '</p>' +
                                    '<p><strong>Email:</strong> ' + decodedData.email + '</p>' +
                                    '<p><strong>Role:</strong> ' + decodedData.type.toUpperCase() + '</p>' +
                                    '<p><strong>ID:</strong> ' + decodedData.id + '</p>' +
                                    '<p><strong>Verified:</strong> ' + new Date().toLocaleString() + '</p>' +
                                '</div>';
                        } else {
                            // Valid QR but unknown type
                            adminResultDiv.innerHTML =
                                '<div class="text-yellow-600">' +
                                    '<p class="font-bold">Valid QR Code Scanned (Unknown Type)</p>' +
                                    '<p><strong>Name:</strong> ' + decodedData.name + '</p>' +
                                    '<p><strong>Email:</strong> ' + decodedData.email + '</p>' +
                                    '<p><strong>Type:</strong> ' + (decodedData.type || 'Unknown').toUpperCase() + '</p>' +
                                    '<p><strong>ID:</strong> ' + decodedData.id + '</p>' +
                                    '<p><strong>Verified:</strong> ' + new Date().toLocaleString() + '</p>' +
                                '</div>';
                        }
                    } else if (data.error) {
                        adminResultDiv.innerHTML = '<p class="text-red-600">Error: ' + data.error + '</p>';
                    } else {
                        adminResultDiv.innerHTML = '<p class="text-red-600">Error processing QR code: Unknown error occurred</p>';
                    }
                })
                .catch(error => {
                    console.error('Admin QR validation error:', error);
                    // Fallback to client-side validation if server validation fails
                    try {
                        const parsedData = JSON.parse(qrData);
                        if (parsedData.id &&
                            (parsedData.type === 'admin' || parsedData.type === 'superadmin')) {
                            // Valid admin QR code - now record attendance
                            recordAdminAttendance(qrData, parsedData);
                        } else if (parsedData.id && parsedData.type === 'user') {
                            adminResultDiv.innerHTML =
                                '<div class="text-blue-600">' +
                                    '<p class="font-bold">Valid Student QR Code Scanned (Not Admin)</p>' +
                                    '<p><strong>Name:</strong> ' + parsedData.name + '</p>' +
                                    '<p><strong>Email:</strong> ' + parsedData.email + '</p>' +
                                    '<p><strong>Role:</strong> ' + parsedData.type.toUpperCase() + '</p>' +
                                    '<p><strong>ID:</strong> ' + parsedData.id + '</p>' +
                                    '<p><strong>Verified:</strong> ' + new Date().toLocaleString() + '</p>' +
                                '</div>';
                        } else {
                            adminResultDiv.innerHTML =
                                '<p class="text-red-600">Error: The scanned QR code does not contain valid information.</p>' +
                                '<p class="text-sm">QR type: ' + (parsedData.type || 'Unknown') + '</p>';
                        }
                    } catch (e) {
                        adminResultDiv.innerHTML =
                            '<p class="text-red-600">Error: Invalid QR code format. Please use a valid admin QR code.</p>' +
                            '<p class="text-sm">Raw data: ' + qrData.substring(0, 100) + (qrData.length > 100 ? '...' : '') + '</p>';
                        console.error('Admin QR data parsing error:', e);
                    }
                });

                // Function to record admin attendance after validation
                function recordAdminAttendance(qrData, userData) {
                    // Send to attendance process endpoint to record admin attendance
                    // Note: date is required by the backend for processScan
                    fetch('/attendance/process-scan', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            qr_data: qrData,
                            date: new Date().toISOString().split('T')[0],  // Use current date
                            // Don't include status here, as processScan will determine default status
                        })
                    })
                    .then(response => response.json())
                    .then(attendanceData => {
                        if (attendanceData.success) {
                            adminResultDiv.innerHTML =
                                '<div class="text-green-600">' +
                                    '<p class="font-bold">Admin Attendance Recorded Successfully!</p>' +
                                    '<p><strong>Name:</strong> ' + userData.name + '</p>' +
                                    '<p><strong>Email:</strong> ' + userData.email + '</p>' +
                                    '<p><strong>Role:</strong> ' + userData.type.toUpperCase() + '</p>' +
                                    '<p><strong>ID:</strong> ' + userData.id + '</p>' +
                                    '<p><strong>Status:</strong> ' + (attendanceData.current_status || 'present').toUpperCase() + '</p>' +
                                    '<p><strong>Time:</strong> ' + new Date().toLocaleString() + '</p>' +
                                '</div>';
                            // Optionally, refresh the admin attendance list to show the new entry
                            // This would require adding a function to refresh the dashboard data
                        } else {
                            adminResultDiv.innerHTML =
                                '<div class="text-yellow-600">' +
                                    '<p class="font-bold">Valid Admin QR Scanned, but attendance not recorded</p>' +
                                    '<p><strong>Reason:</strong> ' + attendanceData.message + '</p>' +
                                    '<p><strong>Name:</strong> ' + userData.name + '</p>' +
                                    '<p><strong>Email:</strong> ' + userData.email + '</p>' +
                                    '<p><strong>Role:</strong> ' + userData.type.toUpperCase() + '</p>' +
                                '</div>';
                        }
                    })
                    .catch(attendanceError => {
                        console.error('Attendance recording error:', attendanceError);
                        // Still show success for scanning but indicate recording failed
                        adminResultDiv.innerHTML =
                            '<div class="text-yellow-600">' +
                                '<p class="font-bold">Valid Admin QR Scanned, but failed to record attendance</p>' +
                                '<p><strong>Name:</strong> ' + userData.name + '</p>' +
                                '<p><strong>Email:</strong> ' + userData.email + '</p>' +
                                '<p><strong>Role:</strong> ' + userData.type.toUpperCase() + '</p>' +
                                '<p><strong>ID:</strong> ' + userData.id + '</p>' +
                                '<p><strong>Error:</strong> Attendance recording failed</p>' +
                            '</div>';
                    });
                }
            });

            // Add the toggleClassStudents function for our new feature
            function toggleClassStudents(classId) {
                const studentsList = document.getElementById('students-list-' + classId);
                if (studentsList.classList.contains('hidden')) {
                    studentsList.classList.remove('hidden');
                } else {
                    studentsList.classList.add('hidden');
                }
            }
        </script>
    </div>
