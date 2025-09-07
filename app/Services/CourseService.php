<?php
namespace App\Services;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use App\Repositories\CourseRepository;
class CourseService
{
    protected CourseRepository $courses;

    public function __construct(CourseRepository $courses)
    {
        $this->courses = $courses;
    }

    public function create(array $data)
    {
        $user = auth('api')->user();
        return $this->courses->create($data, $user->id);
    }

     public function updateCourse(int $id, array $data)
    {
        $course = $this->courses->findById($id);

        if (!$course) {
            return ['error' => 'Course not found', 'status' => 404];
        }

        $user = auth('api')->user();

        if ($course->adminid !== $user->id) {
            return ['error' => 'Forbidden: You do not own this course', 'status' => 403];
        }

        $updated = $this->courses->update($course, $data);

        return ['course' => $updated, 'status' => 200];
    }
}