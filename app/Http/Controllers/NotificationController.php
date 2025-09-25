<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\Request;
use App\Mail\UserNotificationMail;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

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

public function sendEmailNotification(Request $request, $studentId)
{
    $request->validate([
        'subject' => 'required|string',
        'message' => 'required|string',
    ]);

    $student = User::find($studentId);

    if (!$student || !$student->email) {
        return response()->json(['error' => 'Student not found or no email'], 404);
    }

    if ($student->emailNotificationPreferences === false) {
        return response()->json(['error' => 'Student disabled email notifications'], 403);
    }

    Mail::to($student->email)->send(
        new UserNotificationMail($request->subject, $request->message)
    );

    return response()->json(['message' => 'Email notification sent']);
}

}
