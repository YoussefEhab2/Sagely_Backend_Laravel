<?php

namespace App\Http\Controllers;

use App\Services\EnrollmentService;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    protected $service;

    public function __construct(EnrollmentService $service)
    {
        $this->service = $service;
    }

    public function enroll(Request $request, int $courseId)
    {
        $student = auth('api')->user();
        $studentId = $student->id;

        $result = $this->service->enrollStudent($courseId, $studentId);

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], $result['status']);
        }

        return response()->json([
            'message' => 'Successfully enrolled in the course.',
            'data'    => $result['data']
        ], $result['status']);
    }



     public function getByCourse(int $courseId)
    {
       $admin = auth('api')->user();
       $adminId = $admin->id;

        $result = $this->service->getEnrolledStudentsByCourse($courseId, $adminId);

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], $result['status']);
        }

        return response()->json(['students' => $result['data']], 200);
    }

    public function getMyEnrolledCourses()
{
    $student = auth('api')->user();
    $studentId = $student->id;

    $result = $this->service->getEnrolledCoursesByStudent($studentId);

    if (isset($result['error'])) {
        return response()->json(['error' => $result['error']], $result['status']);
    }

    return response()->json(['courses' => $result['data']], 200);
}

public function enrollStudentByAdmin(int $courseId, int $studentId)
{
    $admin = auth('api')->user();
    $adminId = $admin->id;

    $result = $this->service->enrollStudentByAdmin($courseId, $studentId, $adminId);

    if (isset($result['error'])) {
        return response()->json(['error' => $result['error']], $result['status']);
    }

    return response()->json([
        'message' => 'Student enrolled successfully.',
        'data'    => $result['data']
    ], $result['status']);
}
}
