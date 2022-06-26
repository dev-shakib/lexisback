<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\WordController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ConfigController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* Teacher Panel*/

Route::prefix('teacher')->group(function () {
    Route::group(['middleware' => ['auth:api','role:teacher']], function () {
        // put all api protected routes here
        Route::get('list', [AuthController::class, 'teacherList']);
        Route::resource('user',UserController::class);
        Route::resource('activity',ActivityController::class);

        Route::resource('word',WordController::class);
        Route::post('word/create',[WordController::class,'store']);
        Route::post('word/update/{id}',[WordController::class,'update']);
        Route::get('default-config',[ConfigController::class,'index']);
        Route::get('config-list',[ConfigController::class,'ConfigList']);
        Route::post('update-or-create-config',[ConfigController::class,'updateOrCreateConfig']);

        Route::post('logout', [AuthController::class, 'logoutTeacher']);
    });
    Route::post('register', [AuthController::class, 'registerTeacher']);
    Route::post('verify-otp', [AuthController::class, 'verifyOTPTeacher']);
    Route::post('login', [AuthController::class, 'loginTeacher']);
});

/* Teacher Panel*/

