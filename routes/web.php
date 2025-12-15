<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AdminRegistrationController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\IconController;
use App\Http\Controllers\UsersController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Admin and Superadmin registration routes - accessible without authentication
Route::get('/admin/register', [AdminRegistrationController::class, 'showAdminRegistrationForm'])->name('admin.register');
Route::post('/admin/register', [AdminRegistrationController::class, 'registerAdmin'])->name('admin.register.store');

Route::get('/superadmin/register', [AdminRegistrationController::class, 'showSuperAdminRegistrationForm'])->name('superadmin.register');
Route::post('/superadmin/register', [AdminRegistrationController::class, 'registerSuperAdmin'])->name('superadmin.register.store');

// PWA Icon routes
Route::get('/icon/{size}', [IconController::class, 'generateIcon'])->where('size', '[0-9]+');

// QR Code routes
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/qr-code', [QRCodeController::class, 'showUserQR'])->name('qr.show');
    Route::get('/qr-code/generate', [QRCodeController::class, 'generateUserQR']);
    Route::get('/qr-code/full', [QRCodeController::class, 'showFullQR'])->name('qr.full');
    Route::get('/qr-code/download', [QRCodeController::class, 'downloadUserQR'])->name('qr.download');
    Route::get('/admin-qr', [QRCodeController::class, 'showAdminQR'])->name('admin.qr.show');
    Route::get('/admin-qr/download', [QRCodeController::class, 'downloadAdminQR'])->name('admin.qr.download');
    Route::post('/qr-code/debug', [QRCodeController::class, 'debugQRData'])->middleware('role_or:admin,superadmin');

    // Attendance routes
    Route::get('/attendance/scan', [AttendanceController::class, 'scan'])->name('attendance.scan')->middleware('role_or:admin,superadmin');
    Route::post('/attendance/process-scan', [AttendanceController::class, 'processScan'])->middleware('role_or:admin,superadmin');
    Route::post('/attendance/get', [AttendanceController::class, 'getAttendance'])->middleware('role_or:admin,superadmin');
    Route::post('/attendance/store', [AttendanceController::class, 'store'])->middleware('role_or:admin,superadmin');
    Route::get('/attendance/user/{user}', [AttendanceController::class, 'showUserAttendance'])->name('attendance.user');

    // User management routes - only accessible by super_admin
    Route::resource('users', UsersController::class)->middleware('role:super_admin');

    // Class routes
    Route::resource('classes', ClassController::class)->middleware('role_or:admin,superadmin');
    Route::get('/classes/{class}/members', [ClassController::class, 'showMembers'])->name('classes.members')->middleware('role_or:admin,superadmin');

    // Schedule routes
    Route::resource('schedules', ScheduleController::class)->middleware('role_or:admin,superadmin');

    // Report routes
    Route::get('/reports', [ReportController::class, 'index'])->name('reports')->middleware('role_or:admin,superadmin');
    Route::post('/reports/data', [ReportController::class, 'getReportData'])->middleware('role_or:admin,superadmin');
    Route::post('/reports/export-excel', [ReportController::class, 'exportExcel'])->middleware('role_or:admin,superadmin');
    Route::post('/reports/export-csv', [ReportController::class, 'exportCsv'])->middleware('role_or:admin,superadmin');

    // Quick export routes for daily, weekly, and monthly reports
    Route::post('/reports/export/daily-excel', [ReportController::class, 'exportDailyExcel'])->name('reports.export.daily-excel')->middleware('role_or:admin,superadmin');
    Route::post('/reports/export/weekly-excel', [ReportController::class, 'exportWeeklyExcel'])->name('reports.export.weekly-excel')->middleware('role_or:admin,superadmin');
    Route::post('/reports/export/monthly-excel', [ReportController::class, 'exportMonthlyExcel'])->name('reports.export.monthly-excel')->middleware('role_or:admin,superadmin');

    // Import routes
    Route::get('/reports/import', [ReportController::class, 'showImportForm'])->name('reports.import')->middleware('role_or:admin,superadmin');
    Route::post('/reports/import-excel', [ReportController::class, 'importExcel'])->middleware('role_or:admin,superadmin');

    // Attendance history route for regular users
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.history')->middleware('auth');

    // Attendance index route for admins (list all attendance records)
    Route::get('/attendance/index', [AttendanceController::class, 'adminIndex'])->name('attendance.index')->middleware('role_or:admin,superadmin');

    // Superadmin-only routes for managing attendance
    Route::put('/attendance/{id}/status', [AttendanceController::class, 'updateStatus'])->middleware('role:super_admin');
    Route::delete('/attendance/{id}', [AttendanceController::class, 'destroy'])->middleware('role:super_admin');
    Route::get('/cache-management', [AttendanceController::class, 'showCacheManagement'])->middleware('role:super_admin');
    Route::post('/clear-cache', [AttendanceController::class, 'clearCache'])->middleware('role:super_admin');

    // Dashboard routes based on user role
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->isSuperAdmin()) {
            return view('dashboard', ['role' => 'superadmin']);
        } elseif ($user->isAdmin()) {
            return view('dashboard', ['role' => 'admin']);
        } else {
            return view('dashboard', ['role' => 'user']);
        }
    })->name('dashboard');
});

// Health check routes
include_once __DIR__.'/health.php';
