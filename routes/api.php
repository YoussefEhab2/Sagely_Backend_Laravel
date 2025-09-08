<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\CourseController;

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
Route::delete('/announcements/{id}', [AnnouncementController::class, 'delete'])
    ->middleware(['auth:api', 'role:Admin']);
Route::get('/announcements', [AnnouncementController::class, 'index']);

Route::get('/announcements/{id}', [AnnouncementController::class, 'show']);
Route::get('/courses/{courseId}/announcements', [AnnouncementController::class, 'getByCourse'])->middleware('auth:api');


// Course Routes
Route::post('/courses', [CourseController::class, 'create'])->middleware(['auth:api', 'role:Admin']);


Route::put('/courses/{id}', [CourseController::class, 'update'])->middleware('auth:api', 'role:Admin');
Route::delete('/courses/{id}', [CourseController::class, 'delete'])->middleware('auth:api', 'role:Admin');
Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/{id}', [CourseController::class, 'show']);
