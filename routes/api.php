<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AdController;
use App\Http\Controllers\MessageChainController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\NewPasswordController;
use Illuminate\Support\Facades\Route;

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

Route::resource("message_chain", MessageChainController::class);

Route::resource("message", MessageController::class);

Route::resource("categories", CategoryController::class);

Route::get('user', [AuthenticatedSessionController::class, 'getUser']);

//note to self: also make sure kernel is right
Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest');

Route::get('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth');

Route::post('register', [RegisteredUserController::class, 'store'])
    ->middleware('guest');

require __DIR__.'/auth.php';