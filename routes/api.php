<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\RubricController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
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

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [UserController::class, 'create']);

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::group(['middleware' => 'author'], function () {
        Route::post('/users/image', [UserController::class, 'updateImage']);
        Route::post('/news', [NewsController::class, 'store']);
        Route::put('/news/{id}', [NewsController::class, 'update']);
        Route::delete('/news/{id}', [NewsController::class, 'destroy']);
    });

    Route::get('/users', [UserController::class, 'index']);
    Route::get('/news', [NewsController::class, 'index']);
    Route::get('/news/author/{user}', [NewsController::class, 'getAllNewsByUser']);
    Route::get('/news/rubric/{rubric}', [NewsController::class, 'getAllNewsByRubric']);
    Route::get('/news/{id}', [NewsController::class, 'show']);

    Route::post('/rubrics', [RubricController::class, 'store']);


});

