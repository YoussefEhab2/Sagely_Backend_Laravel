<?php


namespace App\Repositories;

use App\Models\Downloadablefile;
use App\Models\Course;
use App\Models\Enrolledstudent;
class DownloadableFileRepository
{
    
    public function createFile(array $data)
    {
        return Downloadablefile::create($data);
    }

    public function findCourse(int $courseId)
    {
        return Course::find($courseId);
    }


    public function findFile(int $fileId, int $courseId)
    {
        return Downloadablefile::where('id', $fileId)
            ->where('courseID', $courseId)
            ->first();
    }

  
    public function fileExistsInCourse(int $courseId, string $fileName, int $excludeFileId = null)
    {
        $query = Downloadablefile::where('courseID', $courseId)
            ->where('name', $fileName);

        if ($excludeFileId) {
            $query->where('id', '!=', $excludeFileId);
        }

        return $query->exists();
    }


    public function updateFile(int $fileId, array $data)
    {
        $file = Downloadablefile::find($fileId);
        if ($file) {
            $file->update($data);
            return $file;
        }
        return null;
    }

    
    public function deleteFile(int $fileId)
    {
        $file = Downloadablefile::find($fileId);
        if ($file) {
            return $file->delete();
        }
        return false;
    }

  
    public function extractPublicIdFromUrl(string $fileUrl)
    {
      
        $urlParts = parse_url($fileUrl);
        $path = $urlParts['path'] ?? '';
        
      
        if (preg_match('/\/v\d+\/(.+)\.\w+$/', $path, $matches)) {
            return $matches[1];
        }
        
        return null;
    }


    public function findFileWithCourse(int $fileId)
    {
        return Downloadablefile::with('course')->find($fileId);
    }

    
    public function isUserEnrolled(int $courseId, int $userId)
    {
        return Enrolledstudent::where('courseID', $courseId)
            ->where('studentID', $userId)
            ->exists();
    }

   
    public function isCourseAdmin(int $courseId, int $userId)
    {
        $course = Course::find($courseId);
        return $course && $course->adminid === $userId;
    }
 public function getByCourseId(int $courseId)
    {
        return Downloadablefile::where('courseID', $courseId)->get();
    }
}