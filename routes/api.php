<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AnnouncementController;

Route::post('/signup', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class,'logout'])->middleware('auth:api');
Route::post('/change-password', [AuthController::class, 'changePassword'])
    ->middleware('auth:api');
Route::get('/me', [AuthController::class,'me'])->middleware('auth:api');
 Route::put('/profile/update', [AuthController::class, 'updateProfile'])->middleware('auth:api');


// Announcement Routes
 Route::post('/announcements', [AnnouncementController::class, 'create'])
    ->middleware(['auth:api', 'role:Admin']);
Route::put('/announcements/{id}', [AnnouncementController::class, 'edit'])
    ->middleware(['auth:api', 'role:Admin']);


