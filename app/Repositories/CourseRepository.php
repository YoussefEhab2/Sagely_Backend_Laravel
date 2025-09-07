<?php

namespace App\Repositories;

use App\Models\Course;

class CourseRepository
{
    public function create(array $data,int $adminId): Course
    {
        $course = new Course();
        $course->name = $data['name'];
        $course->description = $data['description'];
        $course->adminid = $adminId;
        $course->save();
        
        return $course;
    }
     public function update(Course $course, array $data): Course
    {
        $course->update($data);
        return $course;
    }
     public function findById(int $id): ?Course
    {
        return Course::find($id);
    }


}