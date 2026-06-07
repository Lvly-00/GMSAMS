<?php

use App\Http\Controllers\Api\Admin\AdminDashboardController;
use App\Http\Controllers\Api\Admin\AdminReferenceController;
use App\Http\Controllers\Api\Admin\AdminSubjectController;
use App\Http\Controllers\Api\Admin\AdminUserController;
use App\Http\Controllers\Api\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('auth.forgot-password');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('auth.reset-password');
    Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->name('auth.resend-otp');
});

Route::middleware(['auth:sanctum', 'session.activity'])->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::get('/auth/me', [AuthController::class, 'me'])->name('auth.me');

    Route::prefix('admin')->middleware('role:admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/reference', [AdminReferenceController::class, 'index'])->name('reference');

        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
        Route::post('/users/students', [AdminUserController::class, 'storeStudent'])->name('users.students.store');
        Route::post('/users/teachers', [AdminUserController::class, 'storeTeacher'])->name('users.teachers.store');
        Route::post('/users/head-teachers', [AdminUserController::class, 'storeHeadTeacher'])->name('users.head-teachers.store');
        Route::put('/users/{user}/student', [AdminUserController::class, 'updateStudent'])->name('users.students.update');
        Route::put('/users/{user}/teacher', [AdminUserController::class, 'updateTeacher'])->name('users.teachers.update');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

        Route::get('/subjects', [AdminSubjectController::class, 'index'])->name('subjects.index');
        Route::post('/subjects', [AdminSubjectController::class, 'store'])->name('subjects.store');
        Route::post('/subjects/bulk', [AdminSubjectController::class, 'bulk'])->name('subjects.bulk');
        Route::get('/subjects/{subject}', [AdminSubjectController::class, 'show'])->name('subjects.show');
        Route::put('/subjects/{subject}', [AdminSubjectController::class, 'update'])->name('subjects.update');
        Route::delete('/subjects/{subject}', [AdminSubjectController::class, 'destroy'])->name('subjects.destroy');
    });

    Route::prefix('head-teacher')->middleware('role:head_teacher')->group(function () {
        Route::get('/dashboard', fn () => response()->json(['message' => 'Head teacher dashboard placeholder']))->name('head-teacher.dashboard');
    });

    Route::prefix('teacher')->middleware('role:teacher')->group(function () {
        Route::get('/dashboard', fn () => response()->json(['message' => 'Teacher dashboard placeholder']))->name('teacher.dashboard');
    });

    Route::prefix('student')->middleware('role:student')->group(function () {
        Route::get('/dashboard', fn () => response()->json(['message' => 'Student dashboard placeholder']))->name('student.dashboard');
    });
});
