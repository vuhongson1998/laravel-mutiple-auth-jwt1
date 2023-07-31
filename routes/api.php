<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
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

// Route::group([

//     ['middleware' => 'auth:admin-api'],
//     'prefix' => 'auth'

// ], function ($router) {
//     Route::post('register', [AdminController::class, 'register'])->name('register');
//     Route::post('login', [AdminController::class, 'login'])->name('login');
//     Route::post('logout', [AdminController::class, 'logout'])->name('logout');
//     Route::post('me', [AdminController::class, 'me'])->name('me');

// });
Route::post('auth/admin/register', [AdminController::class, 'register']);
Route::post('auth/admin/login', [AdminController::class, 'login']);
Route::group(['middleware' => 'jwt.auth.admin'], function () {
    Route::get('auth/admin/user-info', [AdminController::class, 'getUserInfo']);
    Route::post('auth/admin/logout', [AdminController::class, 'logout']);
});

Route::post('auth/user/register', [UserController::class, 'register']);
Route::post('auth/user/login', [UserController::class, 'login']);
Route::group(['middleware' => 'jwt.auth.user'], function () {
    Route::get('auth/user/user-info', [UserController::class, 'getUserInfo']);
    Route::post('auth/user/logout', [UserController::class, 'logout']);
});