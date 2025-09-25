<?php

namespace App\Repositories;

use App\Models\Notification;

class NotificationRepository
{
    public function create(array $data)
    {
        return Notification::create($data);
    }

    public function getByRecipient(int $recipientId)
    {
        return Notification::where('recipientID', $recipientId)->get();
    }
    public function findById(int $id)
{
    return Notification::find($id);
}
}
