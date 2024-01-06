<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;

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

// Group 1: Authentication
Route::middleware('api')->prefix('auth')->namespace('App\Http\Controllers')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
});

// Group 2: Permissions
Route::middleware('api')->prefix('permissions')->namespace('App\Http\Controllers')->group(function () {
    Route::get('/', [PermissionController::class, 'index']);
    Route::get('/all', [PermissionController::class, 'showAll']);
    Route::post('/create', [PermissionController::class, 'store']);
    Route::put('/update//{id}', [PermissionController::class, 'update']);
    Route::delete('/delete/{id}', [PermissionController::class, 'destroy']);
});

// Group 3: Roles
Route::middleware('api')->prefix('roles')->namespace('App\Http\Controllers')->group(function () {
    Route::get('/', [RoleController::class, 'index']);
    Route::post('/create', [RoleController::class, 'store']);
    Route::put('/update/{id}', [RoleController::class, 'update']);
    Route::delete('/delete/{id}', [RoleController::class, 'destroy']);
});
