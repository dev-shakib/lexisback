<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerifyEmailController;

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
    Route::post('register', [AuthController::class, 'registerTeacher']);
    Route::post('verify-otp', [AuthController::class, 'verifyOTPTeacher']);
    Route::post('login', [AuthController::class, 'loginTeacher']);
    Route::get('list', [AuthController::class, 'teacherList']);
});
/* Teacher Panel*/

// put all api protected routes here
Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
});

//Teacher Activity create panel
Route::post('create-word', [WordController::class, 'store']);
    //Teacher Activity test panel
    //Student Activity access panel
    //configuration panel
    //Manage Words 