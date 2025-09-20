<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected $service;

    public function __construct(NotificationService $service)
    {
        $this->service = $service;
    }
    public function notifyCourseStudents(Request $request, $courseId)
    {
        $request->validate([
            'type' => 'required|string',
            'message' => 'required|string',
        ]);

        $students = $this->service->notifyCourseStudents(
            $courseId,
            $request->type,
            $request->message
        );

        return response()->json([
            'message' => 'Notifications sent successfully',
            'recipients' => $students,
        ], 201);
    }

  
    public function myNotifications()
    {
        $userId = auth()->id();
        $notifications = $this->service->getMyNotifications($userId);

        return response()->json($notifications);
    }

    public function notifyStudent(Request $request, $studentId)
    {
        $request->validate([
            'type'    => 'required|string',
            'message' => 'required|string',
        ]);

        $notification = $this->service->sendToStudent(
            $studentId,
            $request->type,
            $request->message
        );

        if (!$notification) {
            return response()->json(['error' => 'Student not found or invalid role'], 404);
        }

        return response()->json(['message' => 'Notification sent', 'notification' => $notification]);
    }

    public function markAsRead($id)
{
    $userId = auth()->id();

    $notification = $this->service->markAsRead($id, $userId);

    if (!$notification) {
        return response()->json(['error' => 'Notification not found or not yours'], 404);
    }

    return response()->json([
        'message' => 'Notification marked as read',
        'notification' => $notification
    ]);
}

}
