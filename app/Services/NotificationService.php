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


 public function notifyAllExceptSender(int $senderId, string $type, string $message)
    {
        $users = User::where('id', '!=', $senderId)->get();

        foreach ($users as $user) {
            
            if ($user->siteNotificationPreferences) {
                $this->notifications->create([
                    'type'        => $type,
                    'message'     => $message,
                    'recipientID' => $user->id,
                    'status'      => 'unread',
                ]);
            }

            
            if ($user->email && $user->emailNotificationPreferences) {
                Mail::to($user->email)->send(
                    new UserNotificationMail("New $type", $message)
                );
            }
        }

        return $users->pluck('id');
    }

    public function notifyCourseAdminOfSubmission(int $requirementID, int $studentID, string $fileUrl)
{
    $requirement = \App\Models\Requirement::with('course')->find($requirementID);

    if (!$requirement || !$requirement->course) {
        return null;
    }

    $adminId = $requirement->course->adminid;
    $student = User::find($studentID);

    if (!$adminId || !$student) {
        return null;
    }

    $message = "Student {$student->name} submitted a requirement for '{$requirement->title} of the course {$requirement->course->name}'.";
    $type = "Requirement Submission";

    
    $notification = $this->notifications->create([
        'type'        => $type,
        'message'     => $message,
        'recipientID' => $adminId,
        'status'      => 'unread',
    ]);

    $admin = User::find($adminId);
    if ($admin && $admin->email && $admin->emailNotificationPreferences) {
        Mail::to($admin->email)->send(
            new UserNotificationMail("New Requirement Submission", $message . " File: " . $fileUrl)
        );
    }

    return $notification;
}
}
