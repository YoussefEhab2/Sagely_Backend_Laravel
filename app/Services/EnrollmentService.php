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

    public function getEnrolledCoursesByStudent(int $studentId)
{
    $enrollments = $this->repository->getByStudentId($studentId);

    if ($enrollments->isEmpty()) {
        return ['error' => 'No enrolled courses found', 'status' => 404];
    }

    $courses = $enrollments->map(function ($enrollment) {
        return [
            'courseId'   => $enrollment->course->id,
            'courseName' => $enrollment->course->name,
            'description'=> $enrollment->course->description,
        ];
    });

    return ['data' => $courses, 'status' => 200];
}

public function enrollStudentByAdmin(int $courseId, int $studentId, int $adminId, bool $replaceSubmission)
{
    $course = Course::find($courseId);

    if (!$course) {
        return ['error' => 'Course not found', 'status' => 404];
    }

    if ($course->adminid !== $adminId) {
        return ['error' => 'Forbidden: You are not the admin of this course', 'status' => 403];
    }

    // delete old submissions for this student in this course
    $requirements = $course->requirements;
    foreach ($requirements as $requirement) {
        foreach ($requirement->submissions as $submission) {
            if ($submission->studentID === $studentId) {
                $submission->delete();
            }
        }
    }

    if (!$replaceSubmission) {
        return ['message' => 'Submissions deleted only', 'status' => 200];
    }

    // If replaceSubmission = true → enroll after deleting submissions
    if ($this->repository->findEnrollment($courseId, $studentId)) {
        return ['error' => 'Student already enrolled in this course', 'status' => 400];
    }

    $enrollment = $this->repository->createEnrollment($courseId, $studentId);

    return ['data' => $enrollment, 'status' => 201];
}
}
