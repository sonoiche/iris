<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

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

Route::prefix('client')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('login', [LoginController::class, 'login']);
        Route::post('logout', [LoginController::class, 'logout']);
        Route::post('register', [RegisterController::class, 'register']);
        Route::post('check-password', [RegisterController::class, 'checkPassword']);
        Route::post('check-invite-token', [RegisterController::class, 'checkInviteToken']);
        Route::post('send-password-link', [RegisterController::class, 'sendPasswordLink']);
        Route::post('change-password', [RegisterController::class, 'changePassword']);
        Route::get('get-password-reset/{code}', [RegisterController::class, 'passwordReset']);
    });

    Route::middleware('auth:api')->get('/authuser', function (Request $request) {
        return $request->user()->load('role.permissions');
    });
});

// Client Routes
require __DIR__.'/client.php';
