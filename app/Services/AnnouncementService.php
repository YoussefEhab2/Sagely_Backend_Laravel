<?php

namespace App\Services;

use App\Repositories\AnnouncementRepository;
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
        // Ensure publishDate is set to now if not provided
        $data['publishDate'] = $data['publishDate'] ?? Carbon::now();

        return $this->announcements->create([
            'title'      => $data['title'],
            'content'    => $data['content'],
            'category'   => $data['category'] ?? null,
            'publishDate'=> $data['publishDate'],
            'courseID'   => $data['courseID'] ?? null, // optional
        ]);
    }
     public function editAnnouncement(int $id, array $data)
    {
        $announcement = $this->announcements->findById($id);

        if (!$announcement) {
            return null;
        }

        $updateData = [
            'title'       => $data['title'] ?? $announcement->title,
            'content'     => $data['content'] ?? $announcement->content,
            'category'    => $data['category'] ?? $announcement->category,
            'publishDate' => $data['publishDate'] ?? Carbon::now(),
            'courseID'    => $data['courseID'] ?? $announcement->courseID,
        ];

        return $this->announcements->update($announcement, $updateData);
    }
}
