<?php

namespace App\Services;

use App\Repositories\RequirementSubmissionRepository;
use Illuminate\Support\Facades\Log;
use Cloudinary\Cloudinary;
use App\Models\Requirement;
class RequirementSubmissionService
{
    protected $repo;
    protected $cloudinary;

    public function __construct(RequirementSubmissionRepository $repo)
    {
        $this->repo = $repo;
        
        $cloudName = getenv('CLOUDINARY_CLOUD_NAME');
        $apiKey = getenv('CLOUDINARY_API_KEY');
        $apiSecret = getenv('CLOUDINARY_API_SECRET');
        
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => $cloudName,
                'api_key' => $apiKey,
                'api_secret' => $apiSecret,
            ],
            'url' => [
                'secure' => true
            ]
        ]);
    }

    public function submitRequirement(int $requirementID, int $studentID, $file)
    {

        $existing = $this->repo->findByRequirementAndStudent($requirementID, $studentID);
        if ($existing) {
            return ['error' => 'Submission already exists'];
        }

        try {

            $publicId = 'requirement_' . $requirementID . '_student_' . $studentID . '_' . time();
            

            $uploadResult = $this->cloudinary->uploadApi()->upload(
                $file->getRealPath(),
                [
                    'public_id' => $publicId,
                    'folder' => 'requirements',
                    'overwrite' => true,
                    'invalidate' => true
                ]
            );

            $uploadedFileUrl = $uploadResult['secure_url'];

  
            $submission = $this->repo->create([
                'requirementID' => $requirementID,
                'studentID'     => $studentID,
                'fileUrl'       => $uploadedFileUrl,
            ]);

            return $submission;

        } catch (\Exception $e) {
            Log::error('File upload failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'requirementID' => $requirementID,
                'studentID' => $studentID
            ]);
            
            return ['error' => 'File upload failed: ' . $e->getMessage()];
        }
    }


    public function getSubmissionsByRequirement(int $requirementId, int $userId)
    {
        $requirement = Requirement::with('course')->find($requirementId);

        if (!$requirement) {
            return ['error' => 'Requirement not found'];
        }

        if ($requirement->course->adminid !== $userId) {
            return ['error' => 'Unauthorized'];
        }

        return $this->repo->getByRequirementId($requirementId);
    }

    public function getMySubmissionsWithCourse(int $userId)
    {
        return $this->repo->getByStudentIdWithCourse($userId);
    }
}