<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InstructorMasterClassController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/types/{type:slug}', [HomeController::class, 'category'])->name('types.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/cabinet', [InstructorMasterClassController::class, 'index'])->name('cabinet.index');
    Route::get('/cabinet/master-classes/create', [InstructorMasterClassController::class, 'create'])->name('cabinet.create');
    Route::post('/cabinet/master-classes', [InstructorMasterClassController::class, 'store'])->name('cabinet.store');
    Route::get('/cabinet/master-classes/{masterClass}', [InstructorMasterClassController::class, 'show'])->name('cabinet.show');
    Route::get('/cabinet/master-classes/{masterClass}/edit', [InstructorMasterClassController::class, 'edit'])->name('cabinet.edit');
    Route::put('/cabinet/master-classes/{masterClass}', [InstructorMasterClassController::class, 'update'])->name('cabinet.update');
    Route::delete('/cabinet/master-classes/{masterClass}', [InstructorMasterClassController::class, 'destroy'])->name('cabinet.destroy');

    Route::get('/master-classes/{masterClass}/confirm', [EnrollmentController::class, 'confirm'])->name('enrollments.confirm');
    Route::post('/master-classes/{masterClass}/cancel', [EnrollmentController::class, 'cancel'])->name('enrollments.cancel');
    Route::post('/master-classes/{masterClass}/enroll', [EnrollmentController::class, 'store'])->name('enrollments.store');
    Route::delete('/enrollments/{enrollment}', [EnrollmentController::class, 'destroy'])->name('enrollments.destroy');
});
