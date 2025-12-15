<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Banner -->
            <div class="mb-6">
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl p-5 text-black shadow">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div>
                            <h1 class="text-xl font-bold">{{ __('Hello, ') }}{{ Auth::user()->name }}!</h1>
                            <p class="mt-1 text-sm">{{ __('Manage your classes and track attendance') }}</p>
                        </div>
                        <div class="mt-3 md:mt-0">
                            <div class="inline-flex items-center px-3 py-1.5 rounded-md bg-white/20 backdrop-blur-sm dark:bg-gray-800/30 text-sm">
                                <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>{{ now()->format('l, d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-300 hover:shadow-md hover:border-blue-200 dark:hover:border-blue-700 group">
                    <div class="p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 p-2 bg-blue-100 dark:bg-blue-900/30 rounded-md group-hover:bg-blue-200 dark:group-hover:bg-blue-800/40 transition-colors">
                                <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                                </svg>
                            </div>
                            <div class="ml-4 w-0 flex-1">
                                <dl>
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 truncate">{{ __('My Classes') }}</dt>
                                    <dd class="text-xl font-bold text-gray-900 dark:text-white mt-0.5">{{ $totalClasses }}</dd>
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div class="ml-4 w-0 flex-1">
                                <dl>
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 truncate">{{ __('Today\'s Attendance') }}</dt>
                                    <dd class="text-xl font-bold text-gray-900 dark:text-white mt-0.5">{{ $attendanceToday }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-300 hover:shadow-md hover:border-purple-200 dark:hover:border-purple-700 group">
                    <div class="p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 p-2 bg-purple-100 dark:bg-purple-900/30 rounded-md group-hover:bg-purple-200 dark:group-hover:bg-purple-800/40 transition-colors">
                                <svg class="h-5 w-5 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="ml-4 w-0 flex-1">
                                <dl>
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 truncate">{{ __('Week\'s Attendance') }}</dt>
                                    <dd class="text-xl font-bold text-gray-900 dark:text-white mt-0.5">{{ $attendanceThisWeek }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Schedules and Classes -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <!-- Upcoming Schedules -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">{{ __('Upcoming Schedules') }}</h3>
                            <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">{{ __('Your upcoming classes') }}</p>
                        </div>
                        <a href="{{ route('schedules.index') }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium">
                            {{ __('View all') }}
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
                            @endforelse
                        </ul>
                    </div>
                </div>

                <!-- My Classes -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">{{ __('My Classes') }}</h3>
                            <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">{{ __('Classes you are teaching') }}</p>
                        </div>
                        <a href="{{ route('classes.index') }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium">
                            {{ __('View all') }}
                        </a>
                    </div>
                    <div class="p-4">
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($classes as $class)
                                <li class="py-3">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0 p-1.5 bg-indigo-100 dark:bg-indigo-900/30 rounded-md">
                                            <svg class="h-4 w-4 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-medium text-gray-900 dark:text-white truncate">{{ $class->name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $class->description }}</p>
                                        </div>
                                        <div>
                                            <a href="{{ route('qr.show') }}" class="inline-flex items-center px-2.5 py-1 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-700 dark:hover:bg-indigo-800 transition-colors">
                                                <svg class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                                </svg>
                                                QR
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li class="py-6 text-center">
                                    <svg class="mx-auto h-10 w-10 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    <h3 class="mt-1 text-xs font-medium text-gray-900 dark:text-white">No classes assigned</h3>
                                    <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">You haven't been assigned any classes yet</p>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Quick Export Section -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-4">
                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">{{ __('Export Attendance Reports') }}</h3>
                        <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">{{ __('Download reports in Excel format') }}</p>
                    </div>
                </div>
                <div class="p-4">
                    <div class="flex flex-col sm:flex-row sm:flex-wrap gap-3">
                        @if($classes->count() > 0)
                            <form method="POST" action="{{ route('reports.export.daily-excel') }}" class="inline">
                                @csrf
                                <input type="hidden" name="class_id" value="{{ $classes->first()->id }}">
                                <input type="hidden" name="date" value="{{ now()->format('Y-m-d') }}">
                                <button type="submit" class="bg-teal-500 hover:bg-teal-600 text-black font-medium py-1.5 px-3 rounded-md text-sm flex items-center">
                                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    {{ __('Daily Report') }}
                                </button>
                            </form>

                            <form method="POST" action="{{ route('reports.export.weekly-excel') }}" class="inline">
                                @csrf
                                <input type="hidden" name="class_id" value="{{ $classes->first()->id }}">
                                <input type="hidden" name="week_start_date" value="{{ now()->startOfWeek()->format('Y-m-d') }}">
                                <button type="submit" class="bg-teal-500 hover:bg-teal-600 text-black font-medium py-1.5 px-3 rounded-md text-sm flex items-center">
                                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    {{ __('Weekly Report') }}
                                </button>
                            </form>

                            <form method="POST" action="{{ route('reports.export.monthly-excel') }}" class="inline">
                                @csrf
                                <input type="hidden" name="class_id" value="{{ $classes->first()->id }}">
                                <input type="hidden" name="month" value="{{ now()->format('Y-m') }}">
                                <button type="submit" class="bg-teal-500 hover:bg-teal-600 text-black font-medium py-1.5 px-3 rounded-md text-sm flex items-center">
                                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    {{ __('Monthly Report') }}
                                </button>
                            </form>
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No classes found. Please create a class first to export reports.') }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Attendance -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">{{ __('Recent Attendance') }}</h3>
                        <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">{{ __('Latest attendance records for your classes') }}</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('attendance.scan') }}" class="inline-flex items-center text-xs text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 font-medium">
                            <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                            </svg>
                            {{ __('Scanner') }}
                        </a>
                        <a href="{{ route('attendance.index') }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium">
                            {{ __('View all') }}
                        </a>
                    </div>
                </div>
                <div class="p-4">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 sm:rounded-lg overflow-hidden">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sm:px-6 sm:py-3">Student</th>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sm:px-6 sm:py-3">Class</th>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sm:px-6 sm:py-3">Subject</th>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sm:px-6 sm:py-3">Date</th>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sm:px-6 sm:py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($recentAttendances as $attendance)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        <td class="px-3 py-2.5 whitespace-nowrap text-xs font-medium text-gray-900 dark:text-white sm:px-6 sm:py-4">{{ $attendance->user->name }}</td>
                                        <td class="px-3 py-2.5 whitespace-nowrap text-xs text-gray-500 dark:text-gray-300 sm:px-6 sm:py-4">{{ $attendance->schedule->classModel->name }}</td>
                                        <td class="px-3 py-2.5 whitespace-nowrap text-xs text-gray-500 dark:text-gray-300 sm:px-6 sm:py-4">{{ $attendance->schedule->subject->name }}</td>
                                        <td class="px-3 py-2.5 whitespace-nowrap text-xs text-gray-500 dark:text-gray-300 sm:px-6 sm:py-4">{{ $attendance->schedule->date->format('d M') }}</td>
                                        <td class="px-3 py-2.5 whitespace-nowrap sm:px-6 sm:py-4">
                                            <span class="px-2 py-0.5 inline-flex text-xs leading-4 font-semibold rounded-full
                                                {{ $attendance->status === 'present' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200' :
                                                   ($attendance->status === 'late' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200' :
                                                   ($attendance->status === 'absent' ? 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200' :
                                                   'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200')) }}">
                                                {{ ucfirst($attendance->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-3 py-8 text-center text-xs text-gray-500 dark:text-gray-400 sm:px-6 sm:py-10">
                                            <svg class="mx-auto h-10 w-10 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                            <h3 class="mt-1 text-xs font-medium text-gray-900 dark:text-white">No attendance records</h3>
                                            <p class="mt-0.5">Attendance records will appear here once students start attending classes</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
