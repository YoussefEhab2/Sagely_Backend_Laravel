<?php

namespace App\Repositories;

use App\Models\Requirementsubmission;

class RequirementSubmissionRepository
{
    public function create(array $data): Requirementsubmission
    {
        // Create a new instance and set attributes directly
        $submission = new Requirementsubmission();
        $submission->requirementID = $data['requirementID'];
        $submission->studentID = $data['studentID'];
        $submission->fileUrl = $data['fileUrl'];
        $submission->save();

        return $submission;
    }

    public function findByRequirementAndStudent(int $requirementID, int $studentID): ?Requirementsubmission
    {
        return Requirementsubmission::where('requirementID', $requirementID)
                                    ->where('studentID', $studentID)
                                    ->first();
    }

    public function getByRequirementId(int $requirementId)
    {
        return Requirementsubmission::where('requirementID', $requirementId)->get();
    }
}