<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Attendance Reports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label for="classSelect" class="block text-sm font-medium text-gray-700">Select Class</label>
                        <select id="classSelect" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Choose a class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="dateRange" class="block text-sm font-medium text-gray-700">Date Range</label>
                        <select id="dateRange" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                        </select>
                    </div>

                    <div>
                        <label for="fromDate" class="block text-sm font-medium text-gray-700">From Date</label>
                        <input type="date" id="fromDate" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ date('Y-m-d') }}">
                    </div>

                    <div>
                        <label for="toDate" class="block text-sm font-medium text-gray-700">To Date</label>
                        <input type="date" id="toDate" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <div class="mb-6">
                    <button id="generateReport" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
                        Generate Report
                    </button>
                    <button id="exportExcel" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mr-2">
                        Export to Excel
                    </button>
                    <button id="exportCsv" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded mr-2">
                        Export to CSV
                    </button>

                    <!-- Quick export buttons for daily, weekly, monthly reports -->
                    <div class="mt-4">
                        <span class="text-sm font-medium text-gray-700 mr-2">Quick Excel Exports:</span>
                        <button type="button" id="exportDailyExcel" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-3 rounded mr-1 text-sm">
                            Daily
                        </button>
                        <button type="button" id="exportWeeklyExcel" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-3 rounded mr-1 text-sm">
                            Weekly
                        </button>
                        <button type="button" id="exportMonthlyExcel" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-3 rounded text-sm">
                            Monthly
                        </button>
                    </div>
                </div>

                <div id="reportContainer" style="display:none;">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Attendance Report</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200" id="reportTable">
                            <thead class="bg-gray-50">
                                <tr id="reportHeader">
                                    <!-- Headers will be populated dynamically -->
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="reportBody">
                                <!-- Data will be populated dynamically -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const classSelect = document.getElementById('classSelect');
            const dateRange = document.getElementById('dateRange');
            const fromDateInput = document.getElementById('fromDate');
            const toDateInput = document.getElementById('toDate');
            const generateReportBtn = document.getElementById('generateReport');
            const exportExcelBtn = document.getElementById('exportExcel');
            const exportCsvBtn = document.getElementById('exportCsv');
            const reportContainer = document.getElementById('reportContainer');
            const reportHeader = document.getElementById('reportHeader');
            const reportBody = document.getElementById('reportBody');
            
            // Set default date range based on selection
            dateRange.addEventListener('change', function() {
                const today = new Date();
                let fromDate = new Date();
                let toDate = new Date();
                
                switch(this.value) {
                    case 'daily':
                        // Today
                        break;
                    case 'weekly':
                        // Start of week (Monday)
                        const dayOfWeek = today.getDay();
                        const diffToMonday = today.getDate() - dayOfWeek + (dayOfWeek === 0 ? -6 : 1);
                        fromDate = new Date(today.setDate(diffToMonday));
                        today.setDate(fromDate.getDate() + 6);
                        toDate = new Date(today);
                        break;
                    case 'monthly':
                        // First and last day of current month
                        fromDate = new Date(today.getFullYear(), today.getMonth(), 1);
                        toDate = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                        break;
                }
                
                fromDateInput.value = fromDate.toISOString().split('T')[0];
                toDateInput.value = toDate.toISOString().split('T')[0];
            });
            
            // Generate report
            generateReportBtn.addEventListener('click', function() {
                if (!classSelect.value || !fromDateInput.value || !toDateInput.value) {
                    alert('Please select class and date range');
                    return;
                }
                
                fetchReportData();
            });
            
            // Export to Excel
            exportExcelBtn.addEventListener('click', function() {
                if (!classSelect.value || !fromDateInput.value || !toDateInput.value) {
                    alert('Please select class and date range');
                    return;
                }
                
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/reports/export-excel';
                
                // Add CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);
                
                // Add form data
                const classInput = document.createElement('input');
                classInput.type = 'hidden';
                classInput.name = 'class_id';
                classInput.value = classSelect.value;
                form.appendChild(classInput);
                
                const dateRangeInput = document.createElement('input');
                dateRangeInput.type = 'hidden';
                dateRangeInput.name = 'date_range';
                dateRangeInput.value = dateRange.value;
                form.appendChild(dateRangeInput);
                
                const fromDateInput = document.createElement('input');
                fromDateInput.type = 'hidden';
                fromDateInput.name = 'from_date';
                fromDateInput.value = document.getElementById('fromDate').value;
                form.appendChild(fromDateInput);
                
                const toDateInput = document.createElement('input');
                toDateInput.type = 'hidden';
                toDateInput.name = 'to_date';
                toDateInput.value = document.getElementById('toDate').value;
                form.appendChild(toDateInput);
                
                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);
            });
            
            // Export to CSV
            exportCsvBtn.addEventListener('click', function() {
                if (!classSelect.value || !fromDateInput.value || !toDateInput.value) {
                    alert('Please select class and date range');
                    return;
                }

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/reports/export-csv';

                // Add CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);

                // Add form data
                const classInput = document.createElement('input');
                classInput.type = 'hidden';
                classInput.name = 'class_id';
                classInput.value = classSelect.value;
                form.appendChild(classInput);

                const dateRangeInput = document.createElement('input');
                dateRangeInput.type = 'hidden';
                dateRangeInput.name = 'date_range';
                dateRangeInput.value = dateRange.value;
                form.appendChild(dateRangeInput);

                const fromDateInput = document.createElement('input');
                fromDateInput.type = 'hidden';
                fromDateInput.name = 'from_date';
                fromDateInput.value = document.getElementById('fromDate').value;
                form.appendChild(fromDateInput);

                const toDateInput = document.createElement('input');
                toDateInput.type = 'hidden';
                toDateInput.name = 'to_date';
                toDateInput.value = document.getElementById('toDate').value;
                form.appendChild(toDateInput);

                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);
            });

            // Export Daily Excel
            document.getElementById('exportDailyExcel').addEventListener('click', function() {
                if (!classSelect.value) {
                    alert('Please select a class');
                    return;
                }

                const today = new Date().toISOString().split('T')[0]; // Use today's date for daily report
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/reports/export/daily-excel';

                // Add CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);

                // Add form data
                const classInput = document.createElement('input');
                classInput.type = 'hidden';
                classInput.name = 'class_id';
                classInput.value = classSelect.value;
                form.appendChild(classInput);

                const dateInput = document.createElement('input');
                dateInput.type = 'hidden';
                dateInput.name = 'date';
                dateInput.value = today;
                form.appendChild(dateInput);

                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);
            });

            // Export Weekly Excel
            document.getElementById('exportWeeklyExcel').addEventListener('click', function() {
                if (!classSelect.value) {
                    alert('Please select a class');
                    return;
                }

                const today = new Date();
                const dayOfWeek = today.getDay();
                const diffToMonday = today.getDate() - dayOfWeek + (dayOfWeek === 0 ? -6 : 1); // Calculate Monday of current week
                const weekStartDate = new Date(today.setDate(diffToMonday)).toISOString().split('T')[0];

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/reports/export/weekly-excel';

                // Add CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);

                // Add form data
                const classInput = document.createElement('input');
                classInput.type = 'hidden';
                classInput.name = 'class_id';
                classInput.value = classSelect.value;
                form.appendChild(classInput);

                const weekStartInput = document.createElement('input');
                weekStartInput.type = 'hidden';
                weekStartInput.name = 'week_start_date';
                weekStartInput.value = weekStartDate;
                form.appendChild(weekStartInput);

                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);
            });

            // Export Monthly Excel
            document.getElementById('exportMonthlyExcel').addEventListener('click', function() {
                if (!classSelect.value) {
                    alert('Please select a class');
                    return;
                }

                const today = new Date();
                const month = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0'); // Format: YYYY-MM

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/reports/export/monthly-excel';

                // Add CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);

                // Add form data
                const classInput = document.createElement('input');
                classInput.type = 'hidden';
                classInput.name = 'class_id';
                classInput.value = classSelect.value;
                form.appendChild(classInput);

                const monthInput = document.createElement('input');
                monthInput.type = 'hidden';
                monthInput.name = 'month';
                monthInput.value = month;
                form.appendChild(monthInput);

                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);
            });
            
            function fetchReportData() {
                fetch('/reports/data', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        class_id: classSelect.value,
                        from_date: fromDateInput.value,
                        to_date: toDateInput.value
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.students && data.schedules) {
                        renderReportTable(data.students, data.schedules, data.attendances);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
            
            function renderReportTable(students, schedules, attendances) {
                // Clear existing content
                reportHeader.innerHTML = '';
                reportBody.innerHTML = '';
                
                // Create header row
                const headerColumns = ['Student Name', 'Email'];
                schedules.forEach(schedule => {
                    headerColumns.push(`${schedule.date} (${schedule.start_time} - ${schedule.end_time})`);
                });
                
                headerColumns.forEach(col => {
                    const th = document.createElement('th');
                    th.className = 'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider';
                    th.textContent = col;
                    reportHeader.appendChild(th);
                });
                
                // Create data rows
                students.forEach(student => {
                    const row = document.createElement('tr');
                    
                    // Student name and email
                    const nameCell = document.createElement('td');
                    nameCell.className = 'px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900';
                    nameCell.textContent = student.name;
                    row.appendChild(nameCell);
                    
                    const emailCell = document.createElement('td');
                    emailCell.className = 'px-6 py-4 whitespace-nowrap text-sm text-gray-500';
                    emailCell.textContent = student.email;
                    row.appendChild(emailCell);
                    
                    // Attendance for each schedule
                    schedules.forEach(schedule => {
                        const cell = document.createElement('td');
                        cell.className = 'px-6 py-4 whitespace-nowrap text-sm text-gray-500';
                        
                        if (attendances[student.id]) {
                            const attendance = attendances[student.id].find(att => att.schedule_id == schedule.id);
                            if (attendance) {
                                const statusClass = (attendance.status === 'present') ? 'bg-green-100 text-green-800' :
                           (attendance.status === 'late') ? 'bg-yellow-100 text-yellow-800' :
                           (attendance.status === 'absent') ? 'bg-red-100 text-red-800' :
                           'bg-blue-100 text-blue-800';
                                
                                cell.innerHTML = `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">${attendance.status}</span>`;
                            } else {
                                cell.textContent = 'N/A';
                            }
                        } else {
                            cell.textContent = 'N/A';
                        }
                        
                        row.appendChild(cell);
                    });
                    
                    reportBody.appendChild(row);
                });
                
                reportContainer.style.display = 'block';
            }
        });
    </script>
</x-app-layout>