<?php

namespace App\Services;

use App\Repositories\AnnouncementRepository;
use App\Models\Course;

use Carbon\Carbon;

class AnnouncementService
{
    protected AnnouncementRepository $announcements;

    public function __construct(AnnouncementRepository $announcements)
    {
        $this->announcements = $announcements;
    }

    public function create(array $data)
    {
       
        $data['publishDate'] = $data['publishDate'] ?? Carbon::now();

        return $this->announcements->create([
            'title'      => $data['title'],
            'content'    => $data['content'],
            'category'   => $data['category'] ?? null,
            'publishDate'=> $data['publishDate'],
            'courseID'   => $data['courseID'] ?? null,
        ]);
    }
     public function editAnnouncement(int $id, array $data)
    {
        $announcement = $this->announcements->findById($id);

        if (!$announcement) {
            abort(404, 'Announcement not found.');
        }
        $user = auth('api')->user();
        $course = Course::find($announcement->courseID);
        if (!$course || $course->adminid !== $user->id) {
            abort(403, 'You are not authorized to edit this announcement.');
        }
        $updateData = [
            'title'       => $data['title'] ?? $announcement->title,
            'content'     => $data['content'] ?? $announcement->content,
            'category'    => $data['category'] ?? $announcement->category,
            'publishDate' => $data['publishDate'] ?? Carbon::now(),
            'courseID'    => $announcement->courseID,
        ];

        return $this->announcements->update($announcement, $updateData);
    }
    public function deleteAnnouncement(int $id): bool
    {
        $announcement = $this->announcements->findById($id);

        if (!$announcement) {
            abort(404, 'Announcement not found.');
        }

        $user = auth('api')->user();
        $course = Course::find($announcement->courseID);
        if (!$course || $course->adminid !== $user->id) {
           abort(403, 'You are not authorized to delete this announcement.');
        }

        return $this->announcements->delete($id);
    }
    public function getallAnnouncements()
    {
        return $this->announcements->getAll();
    }
    public function getAnnouncement(int $id){
        return $this->announcements->findById($id);
    }

     public function getAnnouncementsByCourseId(int $courseId)
    {
        return $this->announcements->getByCourseId($courseId);
    }
}
