<?php

use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\BiometricController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\JobRoleController;
use App\Http\Controllers\LeaveBalanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\LeavePolicyController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\PerformanceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicJobController;
use App\Http\Controllers\RecruitmentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['guest', 'network'])->group(function () {
    Route::get('login', [LoginController::class, 'show'])->name('login');
    Route::post('login', [LoginController::class, 'authenticate']);
});

Route::middleware(['auth', 'network'])->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Routes
    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Notifications
    Route::post('notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');

    // Attendance Routes
    Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('attendance/clock-in', [AttendanceController::class, 'clockIn'])->name('attendance.clock-in');
    Route::post('attendance/clock-out', [AttendanceController::class, 'clockOut'])->name('attendance.clock-out');
    Route::post('attendance/request-correction', [AttendanceController::class, 'requestCorrection'])->name('attendance.request-correction');

    Route::get('leaves', [LeaveController::class, 'index'])->name('leaves.index');
    Route::get('leaves/apply', [LeaveController::class, 'create'])->name('leaves.create');
    Route::post('leaves', [LeaveController::class, 'store'])->name('leaves.store');

    // Performance Routes
    Route::get('performance', [PerformanceController::class, 'index'])->name('performance.index');

    // Payroll (Self)
    Route::get('payroll', [PayrollController::class, 'index'])->name('payroll.index');
    Route::get('payroll/{user}', [PayrollController::class, 'show'])->name('payroll.show');
    Route::get('payroll/record/{record}/payslip', [PayrollController::class, 'payslip'])->name('payroll.payslip');

    // 201 File / Documents (Publicly accessible but handled by Controller Policy/Logic)
    Route::post('employees/{user}/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::delete('documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
});

// Public Careers / Recruitment Routes
Route::get('careers', [PublicJobController::class, 'index'])->name('careers.index');
Route::get('careers/{job}', [PublicJobController::class, 'show'])->name('careers.show');
Route::post('careers/apply/{job}', [ApplicantController::class, 'submit'])
    ->middleware('throttle:3,1')
    ->name('careers.apply');

Route::middleware(['auth', 'network'])->group(function () {
    // HR & Admin Routes
    Route::middleware('role:HR,Admin')->group(function () {
        Route::get('admin/attendance', [AttendanceController::class, 'adminIndex'])->name('attendance.admin');
        Route::get('admin/attendance/export', [AttendanceController::class, 'exportCSV'])->name('attendance.export');
        Route::post('admin/attendance/corrections/{correction}/approve', [AttendanceController::class, 'approveCorrection'])->name('attendance.correction.approve');
        Route::post('admin/attendance/corrections/{correction}/reject', [AttendanceController::class, 'rejectCorrection'])->name('attendance.correction.reject');

        Route::get('admin/leaves', [LeaveController::class, 'adminIndex'])->name('leaves.admin');
        Route::get('admin/leaves/{leaveRequest}/edit', [LeaveController::class, 'adminEdit'])->name('leaves.admin.edit');
        Route::put('admin/leaves/{leaveRequest}', [LeaveController::class, 'adminUpdate'])->name('leaves.admin.update');
        Route::post('admin/leaves/bulk', [LeaveController::class, 'bulkAction'])->name('leaves.bulk-action');
        Route::post('admin/leaves/{leaveRequest}/approve', [LeaveController::class, 'approve'])->name('leaves.approve');
        Route::post('admin/leaves/{leaveRequest}/reject', [LeaveController::class, 'reject'])->name('leaves.reject');

        Route::patch('admin/applicants/{applicant}/status', [ApplicantController::class, 'updateStatus'])->name('applicants.update-status');
        Route::delete('admin/applicants/{applicant}', [ApplicantController::class, 'destroy'])->name('applicants.destroy');

        Route::get('admin/performance', [PerformanceController::class, 'adminIndex'])->name('performance.admin');
        Route::post('admin/performance', [PerformanceController::class, 'store'])->name('performance.store');
        Route::get('admin/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('admin/reports/data/workforce', [ReportController::class, 'workforceData'])->name('reports.data.workforce');
        Route::get('admin/reports/data/attendance', [ReportController::class, 'attendanceData'])->name('reports.data.attendance');
        Route::get('admin/reports/data/leave', [ReportController::class, 'leaveData'])->name('reports.data.leave');
        Route::get('admin/reports/data/payroll', [ReportController::class, 'payrollData'])->name('reports.data.payroll');
        Route::get('admin/reports/data/performance', [ReportController::class, 'performanceData'])->name('reports.data.performance');
        Route::get('admin/reports/export', [ReportController::class, 'export'])->name('reports.export');
        Route::get('admin/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
        Route::get('admin/audit-logs/{auditLog}', [AuditLogController::class, 'show'])->name('audit-logs.show');

        // Extended Modules (Newly Included)
        Route::get('admin/recruitment', [RecruitmentController::class, 'index'])->name('recruitment.index');
        Route::get('admin/recruitment/export', [RecruitmentController::class, 'exportCSV'])->name('recruitment.export');
        Route::get('admin/recruitment/create', [RecruitmentController::class, 'create'])->name('recruitment.create');
        Route::post('admin/recruitment', [RecruitmentController::class, 'store'])->name('recruitment.store');
        Route::patch('admin/recruitment/{job}/toggle', [RecruitmentController::class, 'toggleStatus'])->name('recruitment.toggle-status');
        Route::delete('admin/recruitment/{job}', [RecruitmentController::class, 'destroy'])->name('recruitment.destroy');

        Route::get('admin/biometrics', [BiometricController::class, 'index'])->name('biometrics.index');
        Route::get('admin/biometrics/devices/create', [BiometricController::class, 'createDevice'])->name('biometrics.devices.create');
        Route::post('admin/biometrics/devices', [BiometricController::class, 'storeDevice'])->name('biometrics.devices.store');
        Route::get('admin/biometrics/devices/{device}/edit', [BiometricController::class, 'editDevice'])->name('biometrics.devices.edit');
        Route::put('admin/biometrics/devices/{device}', [BiometricController::class, 'updateDevice'])->name('biometrics.devices.update');
        Route::delete('admin/biometrics/devices/{device}', [BiometricController::class, 'destroyDevice'])->name('biometrics.devices.destroy');

        Route::get('admin/biometrics/mapping', [BiometricController::class, 'mapping'])->name('biometrics.mapping');
        Route::post('admin/biometrics/mapping', [BiometricController::class, 'storeMapping'])->name('biometrics.mapping.store');
        Route::delete('admin/biometrics/mapping/{mapping}', [BiometricController::class, 'destroyMapping'])->name('biometrics.mapping.destroy');

        Route::get('admin/biometrics', [BiometricController::class, 'index'])->name('biometrics.index');

        Route::get('admin/payroll/export', [PayrollController::class, 'exportCSV'])->name('payroll.export');
        Route::post('admin/payroll/auto', [PayrollController::class, 'autoProcess'])->name('payroll.bulk-generate');
        Route::post('admin/payroll/{user}/draft', [PayrollController::class, 'generateUserDraft'])->name('payroll.user-draft');
        Route::post('admin/payroll/{record}/finalize', [PayrollController::class, 'finalize'])->name('payroll.finalize');
        Route::delete('admin/payroll/{record}', [PayrollController::class, 'destroy'])->name('payroll.destroy');
        Route::post('admin/payroll', [PayrollController::class, 'store'])->name('payroll.store');

        Route::get('admin/biometrics', [BiometricController::class, 'index'])->name('biometrics.index');

        // System Settings Hub
        Route::get('admin/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('admin/settings/global', [SettingController::class, 'update'])->name('settings.update');

        Route::post('admin/settings/leave-types', [LeaveTypeController::class, 'store'])->name('leave-types.store');
        Route::put('admin/settings/leave-types/{leaveType}', [LeaveTypeController::class, 'update'])->name('leave-types.update');
        Route::delete('admin/settings/leave-types/{leaveType}', [LeaveTypeController::class, 'destroy'])->name('leave-types.destroy');

        Route::post('admin/settings/roles', [RoleController::class, 'store'])->name('roles.store');
        Route::put('admin/settings/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
        Route::delete('admin/settings/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

        // 201 File Approvals
        Route::post('admin/documents/{document}/approve', [DocumentController::class, 'approve'])->name('documents.approve');
        Route::post('admin/documents/{document}/reject', [DocumentController::class, 'reject'])->name('documents.reject');

        Route::resource('departments', DepartmentController::class);
        Route::resource('job-roles', JobRoleController::class);
        Route::get('admin/employees/export', [EmployeeController::class, 'exportCSV'])->name('employees.export');
        Route::resource('employees', EmployeeController::class);
        Route::resource('leave-policies', LeavePolicyController::class);
        Route::put('admin/employees/{employee}/leave-balances', [LeaveBalanceController::class, 'update'])->name('leave-balances.update');
        Route::delete('admin/employees/{employee}/leave-balances/{balance}', [LeaveBalanceController::class, 'destroy'])->name('leave-balances.destroy');
    });
});
