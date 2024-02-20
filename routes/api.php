<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RequestUserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\WorkUserController;
use App\Models\RequestUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::controller(AuthController::class)->group(function(){
    Route::post('login', 'login')->name('login');
    Route::post('register', 'register')->name('register');
});

Route::middleware('auth:sanctum')->group(function(){
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::put('/user', [UserController::class, 'update'])->name('users.update');
    Route::post('/user/avatar', [UserController::class, 'updateAvatar'])->name('users.update-avatar');

    Route::apiResource('works', WorkController::class)->names('works');

    Route::apiResource('request-user', RequestUserController::class)->names('request-users');

    Route::apiResource('work-user', WorkUserController::class)->names('work-user');

    Route::apiResource('roles', RoleController::class)->names('roles');
    Route::post('/roles/{role}/assign', [RoleController::class, 'assignRole'])->name('roles.assign');
});
