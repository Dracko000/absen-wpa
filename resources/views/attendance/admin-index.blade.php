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
                                    @if(auth()->user()->isSuperAdmin())
                                        <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sm:px-6 sm:py-3">Actions</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($attendances as $attendance)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" id="attendance-row-{{ $attendance->id }}">
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
                                        <td class="px-3 py-3 text-sm sm:px-6 sm:py-4 whitespace-nowrap" id="status-cell-{{ $attendance->id }}">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ ($attendance->status === 'present') ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200' :
                                                   (($attendance->status === 'late') ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200' :
                                                   (($attendance->status === 'absent') ? 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200' :
                                                   'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200')) }}">
                                                {{ ucfirst($attendance->status) }}
                                            </span>
                                        </td>
                                        @if(auth()->user()->isSuperAdmin())
                                        <td class="px-3 py-3 text-sm sm:px-6 sm:py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <button onclick="openUpdateStatusModal({{ $attendance->id }}, '{{ $attendance->status }}', '{{ addslashes($attendance->notes) }}')"
                                                        class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                    Edit
                                                </button>
                                                <button onclick="confirmDelete({{ $attendance->id }})"
                                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                    Delete
                                                </button>
                                            </div>
                                        </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ auth()->user()->isSuperAdmin() ? 7 : 6 }}" class="px-3 py-8 text-center sm:px-6 sm:py-12">
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

    <!-- Update Status Modal -->
    <div id="update-status-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                Update Attendance Status
                            </h3>
                            <div class="mt-4">
                                <form id="update-status-form">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-4">
                                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                        <select id="status" name="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                            <option value="present">Present</option>
                                            <option value="late">Late</option>
                                            <option value="absent">Absent</option>
                                            <option value="excused">Excused</option>
                                            <option value="sick">Sick</option>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                                        <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"></textarea>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" id="save-status-btn" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Save
                    </button>
                    <button type="button" onclick="closeUpdateStatusModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white dark:bg-gray-600 text-base font-medium text-gray-700 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                Delete Attendance
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Are you sure you want to delete this attendance record? This action cannot be undone.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" id="confirm-delete-btn" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Delete
                    </button>
                    <button type="button" onclick="closeDeleteModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white dark:bg-gray-600 text-base font-medium text-gray-700 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentAttendanceId = null;

        function openUpdateStatusModal(attendanceId, currentStatus, currentNotes) {
            currentAttendanceId = attendanceId;

            // Set current values in the form
            document.getElementById('status').value = currentStatus;
            document.getElementById('notes').value = currentNotes || '';

            // Show modal
            document.getElementById('update-status-modal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeUpdateStatusModal() {
            document.getElementById('update-status-modal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        function confirmDelete(attendanceId) {
            currentAttendanceId = attendanceId;
            document.getElementById('delete-modal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeDeleteModal() {
            document.getElementById('delete-modal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        document.getElementById('save-status-btn').addEventListener('click', function() {
            const status = document.getElementById('status').value;
            const notes = document.getElementById('notes').value;

            fetch(`/attendance/${currentAttendanceId}/status`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-HTTP-Method-Override': 'PUT'
                },
                body: JSON.stringify({
                    status: status,
                    notes: notes
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the status display in the table
                    const statusCell = document.getElementById(`status-cell-${currentAttendanceId}`);
                    let statusClass = '';
                    switch(status) {
                        case 'present':
                            statusClass = 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200';
                            break;
                        case 'late':
                            statusClass = 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200';
                            break;
                        case 'absent':
                            statusClass = 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200';
                            break;
                        default:
                            statusClass = 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200';
                    }

                    statusCell.innerHTML = `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">${status.charAt(0).toUpperCase() + status.slice(1)}</span>`;

                    // Show success message
                    alert(data.message);
                    closeUpdateStatusModal();
                } else {
                    alert('Error updating status: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the status');
            });
        });

        document.getElementById('confirm-delete-btn').addEventListener('click', function() {
            fetch(`/attendance/${currentAttendanceId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the row from the table
                    const row = document.getElementById(`attendance-row-${currentAttendanceId}`);
                    if (row) {
                        row.remove();
                    }

                    // Show success message
                    alert(data.message);
                    closeDeleteModal();
                } else {
                    alert('Error deleting attendance: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the attendance record');
            });
        });

        // Close modals when clicking outside
        document.getElementById('update-status-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeUpdateStatusModal();
            }
        });

        document.getElementById('delete-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    </script>
</x-app-layout>