<?php

use App\Http\Controllers\AdminReportController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ComplaintCategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('app');
})->name('home');

Route::get('/login', function () {
    return redirect('/#login');
})->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('auth.logout');
Route::get('/auth/user', [AuthController::class, 'me'])->name('auth.user');

Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');

Route::middleware(['auth', 'role:admin,guru_uks,pihak_sekolah'])->group(function () {
    Route::get('/dashboard', function () {
        return view('app');
    })->name('dashboard');

    Route::get('/medicines', [MedicineController::class, 'index'])->name('medicines.index');
    Route::get('/medicines/{medicine}', [MedicineController::class, 'show'])->name('medicines.show');

    Route::get('/medical-records', [MedicalRecordController::class, 'index'])->name('medical-records.index');
    Route::get('/medical-records/{medicalRecord}', [MedicalRecordController::class, 'show'])->name('medical-records.show');

    Route::get('/complaint-categories', [ComplaintCategoryController::class, 'index'])->name('complaint-categories.index');

    Route::get('/dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');
    Route::get('/dashboard/alerts', [DashboardController::class, 'criticalAlerts'])->name('dashboard.alerts');
    Route::get('/dashboard/export', [DashboardController::class, 'exportReport'])->name('dashboard.export');
});

Route::middleware(['auth', 'role:admin,guru_uks'])->group(function () {
    Route::post('/medicines', [MedicineController::class, 'store'])->name('medicines.store');
    Route::put('/medicines/{medicine}', [MedicineController::class, 'update'])->name('medicines.update');
    Route::patch('/medicines/{medicine}/stock', [MedicineController::class, 'updateStock'])->name('medicines.update-stock');
    Route::delete('/medicines/{medicine}', [MedicineController::class, 'destroy'])->name('medicines.destroy');

    Route::post('/medical-records', [MedicalRecordController::class, 'store'])->name('medical-records.store');
    Route::put('/medical-records/{medicalRecord}', [MedicalRecordController::class, 'update'])->name('medical-records.update');
    Route::delete('/medical-records/{medicalRecord}', [MedicalRecordController::class, 'destroy'])->name('medical-records.destroy');

    Route::post('/complaint-categories', [ComplaintCategoryController::class, 'store'])->name('complaint-categories.store');
    Route::put('/complaint-categories/{complaintCategory}', [ComplaintCategoryController::class, 'update'])->name('complaint-categories.update');
    Route::delete('/complaint-categories/{complaintCategory}', [ComplaintCategoryController::class, 'destroy'])->name('complaint-categories.destroy');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', function () {
        return view('admin');
    })->name('admin');

    Route::get('/admin/reports', [AdminReportController::class, 'index'])->name('admin.reports.index');
    Route::post('/admin/reports', [AdminReportController::class, 'store'])->name('admin.reports.store');
    Route::patch('/admin/reports/{systemReport}/status', [AdminReportController::class, 'toggleStatus'])->name('admin.reports.toggle-status');
    Route::delete('/admin/reports/{systemReport}', [AdminReportController::class, 'destroy'])->name('admin.reports.destroy');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});
