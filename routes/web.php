<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('user-registration', [UserController::class, "userRegistration"]);
Route::post('user-login', [UserController::class, "userLogin"]);
Route::post('send-otp', [UserController::class, "SendOTPCode"]);
Route::post('verify-otp', [UserController::class, "verifyOTP"]);

Route::post('reset-password', [UserController::class, "resetPassword"])->middleware([TokenVerificationMiddleware::class]);


//Page route

Route::get('userRegistration', [UserController::class, 'RegistrationPage'] );
Route::get('userLogin', [UserController::class, 'LoginPage'] );
Route::get('/sendOtp',[UserController::class,'SendOtpPage']);
Route::get('/verifyOtp',[UserController::class,'VerifyOTPPage']);
Route::get('/resetPassword',[UserController::class,'ResetPasswordPage']);

Route::get('/dashboard',[DashboardController::class,'DashboardPage']);
