<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExamAttemptController;
use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

Route::get('/', LandingController::class)->name('home');

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::get('exams/{exam}/start', [ExamAttemptController::class, 'start'])->name('exams.start');

    Route::get('attempts/{attempt}', [ExamAttemptController::class, 'show'])->name('attempts.show');
    Route::post('attempts/{attempt}/answer', [ExamAttemptController::class, 'answer'])->name('attempts.answer');
    Route::post('attempts/{attempt}/finish', [ExamAttemptController::class, 'finish'])->name('attempts.finish');
    Route::get('attempts/{attempt}/result', [ExamAttemptController::class, 'result'])->name('attempts.result');
});
