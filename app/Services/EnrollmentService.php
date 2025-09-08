<?php

namespace App\Services;

use App\Models\Course;
use App\Repositories\EnrollmentRepository;

class EnrollmentService
{
    protected $repository;

    public function __construct(EnrollmentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function enrollStudent(int $courseId, int $studentId)
    {
        $course = Course::find($courseId);
        if (!$course) {
            return ['error' => 'Course not found', 'status' => 404];
        }

  
        if ($this->repository->findEnrollment($courseId, $studentId)) {
            return ['error' => 'You are already enrolled in this course', 'status' => 400];
        }

     
        $enrollment = $this->repository->createEnrollment($courseId, $studentId);

        return ['data' => $enrollment, 'status' => 201];
    }

    public function getEnrolledStudentsByCourse(int $courseId,int $adminId)
    {
        $course = Course::find($courseId);

        if (!$course) {
            return ['error' => 'Course not found', 'status' => 404];
        }

        if ($course->adminid !== $adminId) {
            return ['error' => 'Forbidden: You are not the admin of this course', 'status' => 403];
        }

        $students = $this->repository->getByCourseId($courseId);

        return ['data' => $students, 'status' => 200];
    }
}
