<?php

namespace App\Repositories;

use App\Models\Announcement;

class AnnouncementRepository
{
    public function create(array $data): Announcement
    {
        return Announcement::create($data);
    }
    public function findById(int $id): ?Announcement
    {
        return Announcement::find($id);
    }

    public function update(Announcement $announcement, array $data): Announcement
    {
        $announcement->update($data);
        return $announcement;
    }
    public function delete(int $id): bool{
       $announcement = Announcement::find($id);

        if ($announcement) {
            return (bool) $announcement->delete();
        }

        return false;
    }

    public function getAll()
    {
        return Announcement::all();
    }
}
