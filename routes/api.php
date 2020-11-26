<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChatsController;

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

/**
 * Authentication group including User data retrieval and validation
 */
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/checkToken', [AuthController::class, 'checkToken']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
    Route::get('/me', [AuthController::class, 'me']);
});

/**
 * Jobs group
 */
Route::group([
    'middleware' => 'api',
    'prefix' => 'job'
], function($router) {
    Route::get('/all', [JobsController::class, 'all']);
    Route::post('/do-job', [JobsController::class, 'doJob']);
});

/**
 * User group
 */
Route::group([
    'middleware' => 'api',
    'prefix' => 'user'
], function($router) {
    Route::get('/top', [UserController::class, 'top']);
});

/**
 * Chat group
 */
Route::group([
    'middleware' => 'api',
    'prefix' => 'chat'
], function($router) {
    Route::post('/fetch', [ChatsController::class, 'fetchMessages']);
    Route::post('/send', [ChatsController::class, 'sendMessage']);
    Route::delete('/delete', [ChatsController::class, 'deleteMessage']);
});