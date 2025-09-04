<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('/signup', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class,'logout'])->middleware('auth:api');
Route::post('/change-password', [AuthController::class, 'changePassword'])
    ->middleware('auth:api');

Route::get('/me', [AuthController::class,'me'])->middleware('auth:api');
 Route::put('/profile/update', [AuthController::class, 'updateProfile'])->middleware('auth:api');;

