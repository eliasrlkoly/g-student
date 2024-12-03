<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdminAuthController;
use App\Http\Controllers\Api\StudentAuthController;
use App\Http\Controllers\Api\InstructorAuthController;



Route::group(['prefix' => 'admin'], function ()
{
    Route::post('register', [AdminAuthController::class, 'register']);
    Route::post('login', [AdminAuthController::class, 'login']);

    Route::middleware(['auth:admin'])->group(function(){
        Route::get('refresh_token', [AdminAuthController::class, 'refreshToken']);
        Route::get('logout', [AdminAuthController::class ,'logout']);
    });
});

Route::group(['prefix' => 'instructor'], function ()
{
    Route::post('register', [InstructorAuthController::class, 'register']);
    Route::post('login', [InstructorAuthController::class, 'login']);

    Route::middleware(['auth:instructor'])->group(function(){
        Route::get('refresh_token', [InstructorAuthController::class, 'refreshToken']);
        Route::get('logout', [InstructorAuthController::class ,'logout']);
    });
});

Route::group(['prefix' => 'student'], function ()
{
    Route::post('register', [StudentAuthController::class, 'register']);
    Route::post('login', [StudentAuthController::class, 'login']);

    Route::middleware(['auth:student'])->group(function(){
        Route::get('refresh_token', [StudentAuthController::class, 'refreshToken']);
        Route::get('logout', [StudentAuthController::class ,'logout']);
    });
});
