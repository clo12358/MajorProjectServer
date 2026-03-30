<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\SymptomController;
use App\Http\Controllers\Api\CycleController;
use App\Http\Controllers\Api\PeriodController;
use App\Http\Controllers\Api\PeriodDayController;
use App\Http\Controllers\Api\DailyLogController;
use App\Http\Controllers\Api\DailySymptomController;
use App\Http\Controllers\Api\JournalController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);

    // Profile
    Route::get('/me', [ProfileController::class, 'show']);
    Route::put('/me', [ProfileController::class, 'update']);

    // Categories & Symptoms
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('symptoms', SymptomController::class);

    // Cycles
    Route::get('/cycles', [CycleController::class, 'index']);
    Route::get('/cycles/{cycle}', [CycleController::class, 'show']);

    // Periods
    Route::get('/periods', [PeriodController::class, 'index']);
    Route::post('/periods', [PeriodController::class, 'store']); 
    Route::get('/periods/{period}', [PeriodController::class, 'show']);
    Route::put('/periods/{period}', [PeriodController::class, 'update']);
    Route::delete('/periods/{period}', [PeriodController::class, 'destroy']);

    // View all days for a period
    Route::get('/periods/{period}/days', [PeriodDayController::class, 'index']);

    // Add or update flow/clots for a specific date
    Route::put('/periods/{period}/days', [PeriodDayController::class, 'upsert']);

    // Daily Logs
    Route::get('/daily-logs', [DailyLogController::class, 'index']);
    Route::post('/daily-logs', [DailyLogController::class, 'store']);
    Route::get('/daily-logs/{dailyLog}', [DailyLogController::class, 'show']);
    Route::put('/daily-logs/{dailyLog}', [DailyLogController::class, 'update']);
    Route::delete('/daily-logs/{dailyLog}', [DailyLogController::class, 'destroy']);

    // Daily Symptoms
    Route::post('/daily-logs/{dailyLog}/symptoms', [DailySymptomController::class, 'store']);
    Route::delete('/daily-logs/{dailyLog}/symptoms/{dailySymptom}', [DailySymptomController::class, 'destroy']);

    // Journal
    Route::get('/journals', [JournalController::class, 'index']);
    Route::get('/daily-logs/{dailyLog}/journal', [JournalController::class, 'show']);   // single entry
    Route::put('/daily-logs/{dailyLog}/journal', [JournalController::class, 'upsert']); 
    
});