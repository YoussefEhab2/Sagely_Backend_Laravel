<?php
// app/Services/DownloadableFileService.php

namespace App\Services;

use App\Repositories\DownloadableFileRepository;
use Cloudinary\Cloudinary;
use Exception;
use Illuminate\Support\Facades\Log;

class DownloadableFileService
{
    protected $repo;
    protected $cloudinary;

    public function __construct()
    {
        $this->repo = new DownloadableFileRepository();
        $this->initializeCloudinary();
    }

    private function initializeCloudinary()
    {
        $cloudName = env('CLOUDINARY_CLOUD_NAME');
        $apiKey = env('CLOUDINARY_API_KEY');
        $apiSecret = env('CLOUDINARY_API_SECRET');
        
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

    public function uploadFile(int $courseId, int $adminId, array $data, $file)
    {
        try {
       
            $course = $this->repo->findCourse($courseId);
            if (!$course) {
                throw new Exception('Course not found', 404);
            }

        
            if ($course->adminid !== $adminId) {
                throw new Exception('Unauthorized. Only course admin can upload files.', 403);
            }

      
            if ($this->repo->fileExistsInCourse($courseId, $data['name'])) {
                throw new Exception('A file with this name already exists in the course', 409);
            }

       
            $publicId = 'course_' . $courseId . '_file_' . time() . '_' . uniqid();
            
            $uploadResult = $this->cloudinary->uploadApi()->upload(
                $file->getRealPath(),
                [
                    'public_id' => $publicId,
                    'folder' => 'course_files',
                    'resource_type' => 'auto'
                ]
            );

            $fileUrl = $uploadResult['secure_url'];

     
            $downloadableFile = $this->repo->createFile([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'fileUrl' => $fileUrl,
                'courseID' => $courseId,
            ]);

            return [
                'success' => true,
                'file' => $downloadableFile,
                'message' => 'File uploaded successfully'
            ];

        } catch (Exception $e) {
            Log::error('File upload failed', [
                'error' => $e->getMessage(),
                'course_id' => $courseId,
                'admin_id' => $adminId
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'code' => $e->getCode() ?: 500
            ];
        }
    }


    public function updateFile(int $courseId, int $fileId, int $adminId, array $data, $file = null)
    {
        try {
            // Check if course exists
            $course = $this->repo->findCourse($courseId);
            if (!$course) {
                throw new Exception('Course not found', 404);
            }

            // Check if user is course admin
            if ($course->adminid !== $adminId) {
                throw new Exception('Unauthorized. Only course admin can update files.', 403);
            }

            // Check if file exists in the course
            $existingFile = $this->repo->findFile($fileId, $courseId);
            if (!$existingFile) {
                throw new Exception('File not found in this course', 404);
            }

            // Check for duplicate file name (excluding current file)
            if (isset($data['name']) && $this->repo->fileExistsInCourse($courseId, $data['name'], $fileId)) {
                throw new Exception('A file with this name already exists in the course', 409);
            }

            $updateData = [];

            // Handle file upload if new file is provided
            if ($file) {
                // Delete old file from Cloudinary
                $publicId = $this->repo->extractPublicIdFromUrl($existingFile->fileUrl);
                if ($publicId) {
                    $this->cloudinary->uploadApi()->destroy($publicId);
                }

                // Upload new file to Cloudinary
                $originalFileName = $file->getClientOriginalName();
                $fileExtension = $file->getClientOriginalExtension();
                $cleanName = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo($originalFileName, PATHINFO_FILENAME));
                $publicId = 'course_' . $courseId . '_' . $cleanName . '_' . time();

                $uploadResult = $this->cloudinary->uploadApi()->upload(
                    $file->getRealPath(),
                    [
                        'public_id' => $publicId,
                        'folder' => 'course_files',
                        'resource_type' => 'raw',
                        'use_filename' => true,
                        'unique_filename' => false,
                        'overwrite' => true,
                        'invalidate' => true
                    ]
                );

                $updateData['fileUrl'] = $uploadResult['secure_url'];
            }

            // Update other fields
            if (isset($data['name'])) {
                $updateData['name'] = $data['name'];
            }
            if (isset($data['description'])) {
                $updateData['description'] = $data['description'];
            }

            // Update database record
            $updatedFile = $this->repo->updateFile($fileId, $updateData);
            if (!$updatedFile) {
                throw new Exception('Failed to update file in database', 500);
            }

            return [
                'success' => true,
                'file' => $updatedFile,
                'message' => 'File updated successfully'
            ];

        } catch (Exception $e) {
            Log::error('File update failed', [
                'error' => $e->getMessage(),
                'course_id' => $courseId,
                'file_id' => $fileId,
                'admin_id' => $adminId
            ]);
            
            return [
                'success' => false,
                'error' => 'File update failed: ' . $e->getMessage(),
                'code' => $e->getCode() ?: 500
            ];
        }
    }

    // Delete File
    public function deleteFile(int $courseId, int $fileId, int $adminId)
    {
        try {
            // Check if course exists
            $course = $this->repo->findCourse($courseId);
            if (!$course) {
                throw new Exception('Course not found', 404);
            }

            // Check if user is course admin
            if ($course->adminid !== $adminId) {
                throw new Exception('Unauthorized. Only course admin can delete files.', 403);
            }

            // Check if file exists in the course
            $file = $this->repo->findFile($fileId, $courseId);
            if (!$file) {
                throw new Exception('File not found in this course', 404);
            }

            // Delete file from Cloudinary
            $publicId = $this->repo->extractPublicIdFromUrl($file->fileUrl);
            if ($publicId) {
                $this->cloudinary->uploadApi()->destroy($publicId);
            }

            // Delete from database
            $deleted = $this->repo->deleteFile($fileId);
            if (!$deleted) {
                throw new Exception('Failed to delete file from database', 500);
            }

            return [
                'success' => true,
                'message' => 'File deleted successfully'
            ];

        } catch (Exception $e) {
            Log::error('File deletion failed', [
                'error' => $e->getMessage(),
                'course_id' => $courseId,
                'file_id' => $fileId,
                'admin_id' => $adminId
            ]);
            
            return [
                'success' => false,
                'error' => 'File deletion failed: ' . $e->getMessage(),
                'code' => $e->getCode() ?: 500
            ];
        }
    }

}