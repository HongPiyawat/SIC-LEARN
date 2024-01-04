<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controller\AuthController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;

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
Route::middleware('api')->prefix('auth')->namespace('App\Http\Controllers')->group(function () {
    Route::post('/login', 'AuthController@login');
    Route::post('/register', 'AuthController@register');
    Route::post('/logout', 'AuthController@logout');
    Route::post('/refresh', 'AuthController@refresh');
    Route::get('/user-profile', 'AuthController@userProfile');
    Route::get('permissions', [PermissionController::class, 'index']);
    Route::get('permissions/all', [PermissionController::class, 'show_all']);
    Route::post('permissions', [PermissionController::class, 'store']);
    Route::put('permissions/{id}', [PermissionController::class, 'update']);
    Route::delete('permissions/{id}', [PermissionController::class, 'destroy']);

});
