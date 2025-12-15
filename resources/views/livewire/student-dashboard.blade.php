<div>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Banner -->
            <div class="mb-8">
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl p-5 text-black shadow">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div>
                            <h1 class="text-xl font-bold">Welcome back, {{ Auth::user()->name }}!</h1>
                            <p class="mt-1 text-black text-sm">Track your attendance and upcoming classes</p>
                        </div>
                        <div class="mt-3 md:mt-0">
                            <div class="inline-flex items-center px-3 py-1.5 rounded-md bg-white/20 backdrop-blur-sm dark:bg-gray-800/30 text-black">
                                <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-black">{{ now()->format('l, d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Class Selection Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">Class Management</h3>
                    <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">Select or change your class</p>
                </div>
                <div class="p-4">
                    @if(session()->has('message'))
                        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-md text-sm">
                            {{ session('message') }}
                        </div>
                    @endif

                    <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4 space-y-4 sm:space-y-0">
                        <div class="flex-1">
                            <label for="classSelect" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Current Class</label>
                            <select wire:model="selectedClassId" id="classSelect" class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">No Class Selected</option>
                                @foreach($availableClasses as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }} - {{ $class->teacher->name ?? 'No Teacher' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="sm:mt-5">
                            <button wire:click="switchClass" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition">
                                Update Class
                            </button>
                        </div>
                    </div>

                    @if(Auth::user()->class)
                        <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-md">
                            <p class="text-sm text-blue-800 dark:text-blue-200">
                                <span class="font-medium">Current Class:</span> {{ Auth::user()->class->name }}
                                @if(Auth::user()->class->teacher)
                                    <span class="block sm:inline sm:ml-2">Teacher: {{ Auth::user()->class->teacher->name }}</span>
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-300 hover:shadow-md hover:border-blue-200 dark:hover:border-blue-700 group">
                    <div class="p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 p-2 bg-blue-100 dark:bg-blue-900/30 rounded-md group-hover:bg-blue-200 dark:group-hover:bg-blue-800/40 transition-colors">
                                <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div class="ml-4 w-0 flex-1">
                                <dl>
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 truncate">Total Attendance</dt>
                                    <dd class="text-xl font-bold text-gray-900 dark:text-white mt-0.5">{{ $attendanceStats['total'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-300 hover:shadow-md hover:border-green-200 dark:hover:border-green-700 group">
                    <div class="p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 p-2 bg-green-100 dark:bg-green-900/30 rounded-md group-hover:bg-green-200 dark:group-hover:bg-green-800/40 transition-colors">
                                <svg class="h-5 w-5 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4 w-0 flex-1">
                                <dl>
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 truncate">Present</dt>
                                    <dd class="text-xl font-bold text-gray-900 dark:text-white mt-0.5">{{ $attendanceStats['present'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-300 hover:shadow-md hover:border-yellow-200 dark:hover:border-yellow-700 group">
                    <div class="p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 p-2 bg-yellow-100 dark:bg-yellow-900/30 rounded-md group-hover:bg-yellow-200 dark:group-hover:bg-yellow-800/40 transition-colors">
                                <svg class="h-5 w-5 text-yellow-600 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4 w-0 flex-1">
                                <dl>
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 truncate">Late</dt>
                                    <dd class="text-xl font-bold text-gray-900 dark:text-white mt-0.5">{{ $attendanceStats['late'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-300 hover:shadow-md hover:border-red-200 dark:hover:border-red-700 group">
                    <div class="p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 p-2 bg-red-100 dark:bg-red-900/30 rounded-md group-hover:bg-red-200 dark:group-hover:bg-red-800/40 transition-colors">
                                <svg class="h-5 w-5 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                            <div class="ml-4 w-0 flex-1">
                                <dl>
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 truncate">Attendance Rate</dt>
                                    <dd class="text-xl font-bold text-gray-900 dark:text-white mt-0.5">{{ $attendanceStats['attendance_rate'] }}%</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- My QR Code and Upcoming Schedules -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
                <!-- My QR Code -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">My QR Code</h3>
                        <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">Show this to your teacher for attendance</p>
                    </div>
                    <div class="p-4 text-center">
                        <a href="{{ route('qr.show') }}" class="inline-block hover:opacity-90 transition-opacity">
                            @livewire('show-user-qr')
                        </a>
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                            Click on the QR code to view full size
                        </p>
                    </div>
                </div>

                <!-- Upcoming Schedules -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Upcoming Schedules</h3>
                            <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">Your upcoming classes</p>
                        </div>
                        <a href="{{ route('schedules.index') }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium">
                            View all
                        </a>
                    </div>
                    <div class="p-4">
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($upcomingSchedules as $schedule)
                                <li class="py-3">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0 p-1.5 bg-blue-100 dark:bg-blue-900/30 rounded-md">
                                            <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-medium text-gray-900 dark:text-white truncate">{{ $schedule->classModel->name }} - {{ $schedule->subject->name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                <span class="inline-flex items-center">
                                                    <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    {{ $schedule->date->format('d M') }}
                                                </span>
                                                <span class="inline-flex items-center ml-2">
                                                    <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    {{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}
                                                </span>
                                            </p>
                                        </div>
                                        <div>
                                            <span class="px-2 py-0.5 inline-flex text-xs leading-4 font-semibold rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200">
                                                {{ ucfirst($schedule->status) }}
                                            </span>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li class="py-6 text-center">
                                    <svg class="mx-auto h-10 w-10 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <h3 class="mt-1 text-xs font-medium text-gray-900 dark:text-white">No upcoming schedules</h3>
                                    <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">Check back later for new schedules</p>
                                </li>
                            @endforelse>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Recent Attendance -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">My Attendance</h3>
                        <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">Your recent attendance records</p>
                    </div>
                    <a href="{{ route('attendance.history') }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium">
                        View all
                    </a>
                </div>
                <div class="p-4">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Class</th>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Subject</th>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Time</th>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($recentAttendances as $attendance)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        <td class="px-4 py-2.5 whitespace-nowrap text-xs font-medium text-gray-900 dark:text-white">{{ $attendance->schedule->classModel->name }}</td>
                                        <td class="px-4 py-2.5 whitespace-nowrap text-xs text-gray-500 dark:text-gray-300">{{ $attendance->schedule->subject->name }}</td>
                                        <td class="px-4 py-2.5 whitespace-nowrap text-xs text-gray-500 dark:text-gray-300">{{ $attendance->schedule->date->format('d M') }}</td>
                                        <td class="px-4 py-2.5 whitespace-nowrap text-xs text-gray-500 dark:text-gray-300">{{ $attendance->check_in_time ? $attendance->check_in_time->format('H:i') : 'N/A' }}</td>
                                        <td class="px-4 py-2.5 whitespace-nowrap">
                                            <span class="px-2 py-0.5 inline-flex text-xs leading-4 font-semibold rounded-full
                                                {{ ($attendance->status === 'present') ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200' :
                                                   (($attendance->status === 'late') ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200' :
                                                   (($attendance->status === 'absent') ? 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200' :
                                                   'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200')) }}">
                                                {{ ucfirst($attendance->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-xs text-gray-500 dark:text-gray-400">
                                            <svg class="mx-auto h-10 w-10 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                            <h3 class="mt-1 text-xs font-medium text-gray-900 dark:text-white">No attendance records</h3>
                                            <p class="mt-0.5">Your attendance records will appear here once you start attending classes</p>
                                        </td>
                                    </tr>
                                @endforelse>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
