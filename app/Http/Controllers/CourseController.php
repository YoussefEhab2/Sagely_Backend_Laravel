<?php

namespace App\Http\Controllers;
use App\Services\CourseService;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }
    public function create(Request $request)
    {
        $user = auth('api')->user();
    if ($user->role !== 'Admin') {
        return response()->json([
            'message' => 'Only admins can create courses'
        ], 403);
    }

    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
    ]);

    $course = $this->courseService->create($request->all());

    return response()->json([
        'message' => 'Course created successfully',
        'course' => $course
    ], 201);
    }

     public function update(Request $request, int $id)
    {
        $request->validate([
            'name'        => 'sometimes|string|max:255',
            'description' => 'nullable|string',
        ]);

        $result = $this->courseService->updateCourse($id, $request->all());

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], $result['status']);
        }

        return response()->json([
            'message' => 'Course updated successfully',
            'course'  => $result['course'],
        ], 200);
    }
}
