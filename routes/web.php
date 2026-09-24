<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExamAttemptController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestionBankController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::get('/', LandingController::class)->name('home');

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('exams/{exam}/start', [ExamAttemptController::class, 'start'])->name('exams.start');

    Route::get('attempts/{attempt}', [ExamAttemptController::class, 'show'])->name('attempts.show');
    Route::post('attempts/{attempt}/answer', [ExamAttemptController::class, 'answer'])->name('attempts.answer');
    Route::post('attempts/{attempt}/finish', [ExamAttemptController::class, 'finish'])->name('attempts.finish');
    Route::get('attempts/{attempt}/result', [ExamAttemptController::class, 'result'])->name('attempts.result');

    Route::get('subscriptions', [SubscriptionController::class, 'plans'])->name('subscriptions.plans');
    Route::post('subscriptions/{plan}/checkout', [SubscriptionController::class, 'checkout'])->name('subscriptions.checkout');
    Route::get('payment/callback', [SubscriptionController::class, 'callback'])->name('payment.callback');

    Route::prefix('bank')->name('bank.')->group(function () {
        Route::get('/', [QuestionBankController::class, 'index'])->name('index');
        Route::get('subjects/{subject}', [QuestionBankController::class, 'subject'])->name('subject');
        Route::get('topics/{topic}', [QuestionBankController::class, 'topic'])->name('topic');
    });
});
