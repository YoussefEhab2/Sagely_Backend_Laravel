<?php

namespace App\Services;

use App\Repositories\NotificationRepository;
use App\Models\Enrolledstudent;
use App\Models\User;
use App\Mail\UserNotificationMail;
use Illuminate\Support\Facades\Mail;
class NotificationService
{
    protected $notifications;

    public function __construct(NotificationRepository $notifications)
    {
        $this->notifications = $notifications;
    }

    public function notifyCourseStudents(int $courseId, string $type, string $message)
    {
        $students = Enrolledstudent::where('courseID', $courseId)->pluck('studentID');

        foreach ($students as $studentId) {
            $student = User::find($studentId);
            if (!$student || !$student->siteNotificationPreferences) {
                continue;
            }

            $this->notifications->create([
                'type'        => $type,
                'message'     => $message,
                'recipientID' => $studentId,
                'status'      => 'unread',
            ]);

            if ($student->email && $student->emailNotificationPreferences) {
                Mail::to($student->email)->send(
                    new UserNotificationMail("New $type", $message)
                );
            }
        }

        return $students;
    }

    public function getMyNotifications(int $studentId)
    {
        return $this->notifications->getByRecipient($studentId);
    }
     public function sendToStudent(int $studentId, string $type, string $message)
    {
 
        $student = User::find($studentId);
        if (!$student || $student->role !== 'Student' || !$student->siteNotificationPreferences) {
            return null;
        }

        $notification=$this->notifications->create([
            'type'        => $type,
            'message'     => $message,
            'recipientID' => $studentId,
            'status'      => 'unread',
        ]);

        if ($student->email && $student->emailNotificationPreferences) {
            Mail::to($student->email)->send(
                new UserNotificationMail("New $type", $message)
            );
        }
        return $notification;
    }

public function markAsRead(int $notificationId, int $userId)
{
    $notification = $this->notifications->findById($notificationId);

    if (!$notification || $notification->recipientID !== $userId) {
        return null;
    }

    $notification->status = 'read';
    $notification->save();

    return $notification;
}    
}
