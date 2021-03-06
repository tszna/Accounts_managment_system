<?php

use App\Http\Controllers\UserController;
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

Route::prefix('user')
    ->middleware(['api'])//, 'auth:api', 'browsercheck'])
    ->group(function () {
        Route::get('/', [UserController::class, 'getAll']);
        Route::get('/{user}', [UserController::class, 'get']);

        Route::post('/', [UserController::class, 'store']);
        Route::patch('/', [UserController::class, 'update']);

        Route::delete('/{user}', [UserController::class, 'delete']);
    });

include 'Auth/auth.php';
