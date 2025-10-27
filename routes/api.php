<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\SyncController;
use App\Http\Controllers\Api\PatientController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public API routes (for PWA demo - in production, add authentication)
Route::prefix('v1')->group(function () {
    // Task CRUD operations
    Route::apiResource('tasks', TaskController::class);

    // Patient CRUD operations
    Route::apiResource('patients', PatientController::class);

    // Add treatment to patient
    Route::post('patients/{patient}/treatments', [PatientController::class, 'addTreatment']);

    // Sync operations
    Route::post('sync', [SyncController::class, 'sync']);
    Route::get('sync/status', [SyncController::class, 'status']);
});

// Protected routes (when authentication is needed)
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    // Add protected API routes here
});
