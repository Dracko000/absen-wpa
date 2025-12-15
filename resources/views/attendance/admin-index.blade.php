<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Attendance Records') }}
        </h2>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-4 sm:p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <!-- Page Header -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Attendance Records</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage and view attendance records</p>
                        </div>
                    </div>

                    <!-- Attendance Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sm:px-6 sm:py-3">Student</th>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sm:px-6 sm:py-3">Class</th>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sm:px-6 sm:py-3">Subject</th>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sm:px-6 sm:py-3">Date</th>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sm:px-6 sm:py-3">Time</th>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sm:px-6 sm:py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($attendances as $attendance)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        <td class="px-3 py-3 text-sm sm:px-6 sm:py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8 sm:h-10 sm:w-10">
                                                    <img class="h-8 w-8 sm:h-10 sm:w-10 rounded-full" src="{{ $attendance->user->profile_photo_url }}" alt="{{ $attendance->user->name }}">
                                                </div>
                                                <div class="ml-3 sm:ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $attendance->user->name }}</div>
                                                    <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">{{ $attendance->user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-3 py-3 text-sm sm:px-6 sm:py-4 whitespace-nowrap text-gray-500 dark:text-gray-300">
                                            {{ $attendance->schedule->classModel->name }}
                                        </td>
                                        <td class="px-3 py-3 text-sm sm:px-6 sm:py-4 whitespace-nowrap text-gray-500 dark:text-gray-300">
                                            {{ $attendance->schedule->subject->name }}
                                        </td>
                                        <td class="px-3 py-3 text-sm sm:px-6 sm:py-4 whitespace-nowrap text-gray-500 dark:text-gray-300">
                                            {{ $attendance->schedule->date->format('M d, Y') }}
                                        </td>
                                        <td class="px-3 py-3 text-sm sm:px-6 sm:py-4 whitespace-nowrap text-gray-500 dark:text-gray-300">
                                            {{ $attendance->check_in_time ? $attendance->check_in_time->format('H:i') : 'N/A' }}
                                        </td>
                                        <td class="px-3 py-3 text-sm sm:px-6 sm:py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
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
                                        <td colspan="6" class="px-3 py-8 text-center sm:px-6 sm:py-12">
                                            <svg class="mx-auto h-10 w-10 sm:h-12 sm:w-12 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No attendance records</h3>
                                            <p class="mt-1 text-xs sm:text-sm text-gray-500 dark:text-gray-400">Attendance records will appear here once students start attending classes</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $attendances->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>