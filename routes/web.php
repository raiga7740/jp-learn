<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;

// Landing
Route::get('/', function () {
    return view('landing');
})->name('landing');

// Auth (guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
});

// Dashboard & Learning (auth only)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard home
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Learning sections
    Route::get('/materi', [DashboardController::class, 'materials'])->name('dashboard.materials');
    Route::get('/quiz', [DashboardController::class, 'quizzes'])->name('dashboard.quizzes');
    Route::get('/progress', [DashboardController::class, 'progress'])->name('dashboard.progress');

    // Materials
    Route::get('/materi/{material:slug}',    [DashboardController::class, 'material'])->name('material.show');
    Route::post('/materi/{material:slug}/selesai', [DashboardController::class, 'completeMaterial'])->name('material.complete');

    // Quizzes
    Route::get('/quiz/{quiz}',       [DashboardController::class, 'quiz'])->name('quiz.show');
    Route::post('/quiz/{quiz}',      [DashboardController::class, 'submitQuiz'])->name('quiz.submit');
    Route::get('/quiz/{quiz}/hasil', [DashboardController::class, 'quizResult'])->name('quiz.result');

    // Profile
    Route::get('/profil',              [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profil/info',         [ProfileController::class, 'updateInfo'])->name('profile.update-info');
    Route::put('/profil/kata-sandi',   [ProfileController::class, 'updatePassword'])->name('profile.update-password');
});
