<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AdController;
use App\Http\Controllers\PostalCodeController;
use App\Http\Controllers\MessageChainController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\NewPasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\VerifyEmailController;

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
Route::resource("ads", AdController::class);

Route::post("ads/{ad}", [AdController::class, "update"]);

Route::post("ads/{ad}/bid", [AdController::class, "bid"]);

Route::get("ads/{ad}/upgrade", [AdController::class, "upgrade"]);

Route::get("postalCodes", [PostalCodeController::class, "index"]);

Route::resource("message_chain", MessageChainController::class);

Route::resource("message", MessageController::class);

Route::post("message/send_notification", [MessageController::class, "sendEmail"]);

Route::resource("categories", CategoryController::class);

Route::get('user', [AuthenticatedSessionController::class, 'getUser']);

//note to self: also make sure kernel is right
Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest');

Route::get('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth');

Route::post('register', [RegisteredUserController::class, 'store'])
    ->middleware('guest');

Route::put("user/{user}", [RegisteredUserController::class, "update"]);

// routes form auth.php
Route::get('/register', [RegisteredUserController::class, 'create'])
                ->middleware('guest')
                ->name('register');

Route::post('/register', [RegisteredUserController::class, 'store'])
                ->middleware('guest');

Route::get('/login', [AuthenticatedSessionController::class, 'create'])
                ->middleware('guest')
                ->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
                ->middleware('guest');

Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
                ->middleware('guest')
                ->name('password.request');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
                ->middleware('guest')
                ->name('password.email');

Route::get('/reset-password/{token}', function() {
    return view('welcome'); 
})
                ->middleware('guest')
                ->name('password.reset');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
                ->middleware('guest')
                ->name('password.update');

Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])
                ->middleware('auth')
                ->name('verification.notice');

Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
                ->middleware(['auth', 'signed', 'throttle:6,1'])
                ->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware(['auth', 'throttle:6,1'])
                ->name('verification.send');

Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->middleware('auth')
                ->name('password.confirm');

Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])
                ->middleware('auth');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
                ->middleware('auth')
                ->name('logout');