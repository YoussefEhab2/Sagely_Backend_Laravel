<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\RequirementController;
use App\Http\Controllers\RequirementSubmissionController;
use App\Http\Controllers\NotificationController;

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
// Enrollment Routes
 Route::post('/courses/{courseId}/enroll', [EnrollmentController::class, 'enroll'])->middleware('auth:api', 'role:Student');
 Route::get('/courses/{courseId}/students', [EnrollmentController::class, 'getByCourse'])->middleware('auth:api', 'role:Admin');

 // Requirement Routes
 Route::post('/requirements', [RequirementController::class, 'store'])->middleware(['auth:api', 'role:Admin']);
  Route::put('/requirements/{id}', [RequirementController::class, 'update'])->middleware(['auth:api', 'role:Admin']);
  Route::delete('/requirements/{id}', [RequirementController::class, 'deleteRequirement'])->middleware(['auth:api', 'role:Admin']);
  Route::get('/courses/{courseId}/requirements', [RequirementController::class, 'getRequirementsByCourse'])->middleware('auth:api');
  // Requirement Submission Route
  Route::post('/requirements/{id}/submit', [RequirementSubmissionController::class, 'submit'])->middleware('auth:api','role:Student');
  Route::get('/requirements/{id}/submissions', [RequirementSubmissionController::class, 'getSubmissions'])->middleware('auth:api','role:Admin');
  Route::get('/me/submissions', [RequirementSubmissionController::class, 'getMySubmissions'])->middleware('auth:api','role:Student');
Route::get('/requirements/{courseId}/course/submissions', [RequirementSubmissionController::class, 'getSubmissionsByCourse'])->middleware('auth:api','role:Admin');
  // Notification Routes
  Route::post('/courses/{courseId}/notify', [NotificationController::class, 'notifyCourseStudents'])->middleware(['auth:api', 'role:Admin']);
Route::get('/notifications/me', [NotificationController::class, 'myNotifications'])->middleware(['auth:api', 'role:Student']);
Route::post('/students/{studentId}/notify', [NotificationController::class, 'notifyStudent'])->middleware(['auth:api', 'role:Admin']); 
Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->middleware(['auth:api', 'role:Student']);
Route::post('/notifications/email/{studentId}', [NotificationController::class, 'sendEmailNotification'])->middleware(['auth:api', 'role:Admin']);
