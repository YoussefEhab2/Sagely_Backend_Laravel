<?php

namespace App\Repositories;

use App\Models\Enrolledstudent;

class EnrollmentRepository
{
    public function findEnrollment(int $courseId, int $studentId)
    {
        return Enrolledstudent::where('courseID', $courseId)
            ->where('studentID', $studentId)
            ->first();
    }

    public function createEnrollment(int $courseId, int $studentId): Enrolledstudent
    {
        $enrollment = new Enrolledstudent();
        $enrollment->courseID  = $courseId;
        $enrollment->studentID = $studentId;
        $enrollment->save();
        return $enrollment;
    }

    public function getByCourseId(int $courseId)
    {
        return Enrolledstudent::with('user')
            ->where('courseID', $courseId)
            ->get();
    }
}
