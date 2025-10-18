<?php

namespace App\Services;

use App\Repositories\RequirementRepository;
use App\Models\Course;

class RequirementService
{
    protected RequirementRepository $requirements;

    public function __construct(RequirementRepository $requirements)
    {
        $this->requirements = $requirements;
    }

    public function createRequirement(array $data, int $adminId)
    {
        $course = Course::find($data['courseID']);

        if (!$course) {
            return ['error' => 'Course not found', 'status' => 404];
        }

        if ($course->adminid !== $adminId) {
            return ['error' => 'Forbidden: You are not the admin of this course', 'status' => 403];
        }

        $requirement = $this->requirements->create([
            'title'       => $data['title'],
            'description' => $data['description'] ?? null,
            'courseID'    => $data['courseID'],
        ]);

        return ['data' => $requirement, 'status' => 201];
    }
     public function updateRequirement(int $id, array $data)
    {
        $requirement = $this->requirements->find($id);

        if (!$requirement) {
            return ['error' => 'Requirement not found', 'status' => 404];
        }

        $course = Course::find($requirement->courseID);

        if (!$course) {
            return ['error' => 'Course not found', 'status' => 404];
        }
$user = auth('api')->user();

        if ($course->adminid !== $user->id) {
            return ['error' => 'Forbidden: You are not the admin of this course', 'status' => 403];
        }

        $updatedRequirement = $this->requirements->update($requirement, $data);

        return ['data' => $updatedRequirement, 'status' => 200];
    }

     public function deleteRequirement(int $id): bool|string
    {
        $requirement = $this->requirements->find($id);

        if (!$requirement) {
            return "Requirement not found.";
        }

        $user = auth('api')->user();

        if ($requirement->course->adminid !== $user->id) {
            return "Unauthorized. You are not the admin of this course.";
        }

        return $this->requirements->delete($requirement);
    }
    public function getRequirementsByCourse(int $courseId)
    {
        return $this->requirements->getByCourseId($courseId);
    }
}
