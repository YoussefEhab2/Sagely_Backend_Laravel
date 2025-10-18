<?php

namespace App\Repositories;

use App\Models\Requirement;

class RequirementRepository
{
    public function create(array $data): Requirement
    {
        return Requirement::create($data);
    }
  public function find(int $id): ?Requirement
    {
        return Requirement::find($id);
    }
    public function update(Requirement $requirement, array $data): Requirement
    {

        $requirement->update($data);

        return $requirement;
    }
    public function delete(Requirement $requirement): bool
    {
        return $requirement->delete();
    }
     public function getByCourseId(int $courseId)
    {
        return Requirement::where('courseID', $courseId)->get();
    }
    
}
