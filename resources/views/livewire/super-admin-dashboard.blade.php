<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Super Admin Dashboard') }}
        </h2>
    </x-slot>

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
                                            <a href="#" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-full shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                                                View
                                            </a>
                                        </div>
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

                <!-- Recent Attendance -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Attendance</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Latest attendance records</p>
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
                                                    {{ $attendance->status === 'present' ? 'bg-green-100 text-green-800' :
                                                       ($attendance->status === 'late' ? 'bg-yellow-100 text-yellow-800' :
                                                       ($attendance->status === 'absent' ? 'bg-red-100 text-red-800' :
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
        </div>
    </div>
</div>
