<?php

use App\Presentation\Http\Controllers\CompanyController;
use App\Presentation\Http\Controllers\EnterpriseUserController;
use App\Presentation\Http\Controllers\PlanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
|
*/

// Plans Routes
Route::prefix('plans')->group(function () {
    Route::get('/', [PlanController::class, 'index']);
    Route::post('/', [PlanController::class, 'store']);
    Route::get('/{id}', [PlanController::class, 'show']);
    Route::put('/{id}', [PlanController::class, 'update']);
    Route::delete('/{id}', [PlanController::class, 'destroy']);
});

// Companies Routes
Route::prefix('companies')->group(function () {
    Route::get('/', [CompanyController::class, 'index']);
    Route::post('/', [CompanyController::class, 'store']);
    Route::get('/{id}', [CompanyController::class, 'show']);
    Route::put('/{id}', [CompanyController::class, 'update']);
    Route::delete('/{id}', [CompanyController::class, 'destroy']);
    Route::post('/{id}/subscribe', [CompanyController::class, 'subscribeToPlan']);
    Route::post('/{id}/cancel-subscription', [CompanyController::class, 'cancelSubscription']);
});

// Enterprise Users Routes
Route::prefix('enterprise-users')->group(function () {
    Route::get('/', [EnterpriseUserController::class, 'index']);
    Route::post('/', [EnterpriseUserController::class, 'store']);
    Route::get('/{id}', [EnterpriseUserController::class, 'show']);
    Route::put('/{id}', [EnterpriseUserController::class, 'update']);
    Route::delete('/{id}', [EnterpriseUserController::class, 'destroy']);
    Route::post('/login', [EnterpriseUserController::class, 'login']);
});
