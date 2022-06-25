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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

//verify OTP
Route::post('verify-otp', [AuthController::class, 'verifyOTP']);

// // Verify email
// Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
//     ->middleware(['signed', 'throttle:6,1'])
//     ->name('verification.verify');

// // Resend link to verify email
// Route::post('/email/verify/resend', function (Request $request) {
//     $request->user()->sendEmailVerificationNotification();
//     return back()->with('message', 'Verification link sent!');
// })->middleware(['auth:api', 'throttle:6,1'])->name('verification.send');

// put all api protected routes here
Route::middleware('auth:api')->group(function () {
    Route::get('user-detail', [AuthController::class, 'userDetail']);
    Route::post('logout', [AuthController::class, 'logout']);
});



//Teacher Activity create panel
//Teacher Activity test panel
//Student Activity access panel
//configuration panel
//Word 