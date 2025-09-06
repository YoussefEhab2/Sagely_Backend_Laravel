<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AnnouncementService;

class AnnouncementController extends Controller
{
    protected AnnouncementService $service;

    public function __construct(AnnouncementService $service)
    {
        $this->service = $service;
    }

    public function create(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'category'=> 'nullable|string|max:100',
            'courseID'=> 'nullable|integer|exists:course,id', 
        ]);

        $announcement = $this->service->create($request->all());

        return response()->json([
            'message' => 'Announcement created successfully',
            'announcement' => $announcement
        ], 201);
    }
    public function edit(Request $request, $id)
{
    $validated = $request->validate([
        'title'     => 'sometimes|string|max:255',
        'content'   => 'sometimes|string',
        'category'  => 'nullable|string|max:100',
        'courseID'  => 'nullable|integer|exists:course,id',
    ]);

    $announcement = $this->service->editAnnouncement($id, $validated);

    if (!$announcement) {
        return response()->json(['error' => 'Announcement not found'], 404);
    }

    return response()->json([
        'message' => 'Announcement updated successfully',
        'announcement' => $announcement,
    ]);
}

public function delete($id)
{
    $deleted = $this->service->deleteAnnouncement($id);

    if (!$deleted) {
        return response()->json(['error' => 'Announcement not found'], 404);
    }

    return response()->json(['message' => 'Announcement deleted successfully']);
}

public function index()
{
    $announcements = $this->service->getallAnnouncements();

    return response()->json(['announcements' => $announcements]);
}
public function show(int $id)
    {
        $announcement = $this->service->getAnnouncement($id);

        if (!$announcement) {
            return response()->json(['error' => 'Announcement not found'], 404);
        }

        return response()->json(['announcement' => $announcement]);
    }

}
