<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\SymptomController;
use App\Http\Controllers\Api\CycleController;
use App\Http\Controllers\Api\PeriodController;
use App\Http\Controllers\Api\DailyLogController;
use App\Http\Controllers\Api\DailySymptomController;
use App\Http\Controllers\Api\JournalController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // profile
    Route::get('/me', [ProfileController::class, 'show']);
    Route::put('/me', [ProfileController::class, 'update']);

    // categories + symptoms
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('symptoms', SymptomController::class);

    // cycles + periods
    Route::get('/cycles', [CycleController::class, 'index']);
    Route::get('/cycles/{cycle}', [CycleController::class, 'show']);

    Route::get('/periods', [PeriodController::class, 'index']);
    Route::post('/periods', [PeriodController::class, 'store']); // <-- creates new cycle + closes previous
    Route::put('/periods/{period}', [PeriodController::class, 'update']);
    Route::delete('/periods/{period}', [PeriodController::class, 'destroy']);

    // daily logs
    Route::get('/daily-logs', [DailyLogController::class, 'index']);
    Route::post('/daily-logs', [DailyLogController::class, 'store']);
    Route::get('/daily-logs/{dailyLog}', [DailyLogController::class, 'show']);
    Route::put('/daily-logs/{dailyLog}', [DailyLogController::class, 'update']);
    Route::delete('/daily-logs/{dailyLog}', [DailyLogController::class, 'destroy']);

    // daily symptoms
    Route::post('/daily-logs/{dailyLog}/symptoms', [DailySymptomController::class, 'store']);
    Route::delete('/daily-logs/{dailyLog}/symptoms/{dailySymptom}', [DailySymptomController::class, 'destroy']);

    // journal
    Route::put('/daily-logs/{dailyLog}/journal', [JournalController::class, 'upsert']);
});