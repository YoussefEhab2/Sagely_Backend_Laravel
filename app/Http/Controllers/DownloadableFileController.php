<?php

namespace App\Http\Controllers;

use App\Services\DownloadableFileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class DownloadableFileController extends Controller
{
    protected $service;

    public function __construct(DownloadableFileService $service)
    {
        $this->service = $service;
    }

   public function store(Request $request, int $courseId)
{
    $request->validate([
        'file' => 'required|file',
    ]);

    $file = $request->file('file');
    $extension = $file->getClientOriginalExtension(); // pdf, docx, etc.
    $publicId = 'course_' . $courseId . '_file_' . time();

    try {
        $uploadResult = $this->cloudinary->uploadApi()->upload(
            $file->getRealPath(),
            [
                'public_id'   => $publicId,
                'folder'      => 'downloadable_files',
                'overwrite'   => true,
                'invalidate'  => true,
                'resource_type' => 'raw', // force Cloudinary to treat as file, not image
            ]
        );

        $uploadedFileUrl = $uploadResult['secure_url'];

        return response()->json([
            'message' => 'File uploaded successfully',
            'url' => $uploadedFileUrl,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'File upload failed: ' . $e->getMessage()
        ], 500);
    }
}

    public function listFiles($courseId)
    {
        $admin = auth('api')->user();
        $adminId = $admin->id;

        $result = $this->service->listFiles((int)$courseId, $adminId);

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], $result['status']);
        }

        return response()->json(['files' => $result['data']], 200);
    }


    public function updateFile(Request $request, $courseId, $fileId)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'file' => 'sometimes|file|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'details' => $validator->errors()
            ], 422);
        }

        // Get authenticated admin ID
        $adminId = auth('api')->user()->id;

        // Call service to handle file update
        $result = $this->service->updateFile(
            $courseId,
            $fileId,
            $adminId,
            $request->only(['name', 'description']),
            $request->file('file')
        );

        // Return response
        if ($result['success']) {
            return response()->json([
                'message' => $result['message'],
                'file' => $result['file']
            ], 200);
        } else {
            $code = $result['code'] ?? 500;
            return response()->json([
                'error' => $result['error']
            ], $code);
        }
    }

    // Delete File
    public function deleteFile($courseId, $fileId)
    {
        // Get authenticated admin ID
        $adminId = auth('api')->user()->id;

        // Call service to handle file deletion
        $result = $this->service->deleteFile($courseId, $fileId, $adminId);

        // Return response
        if ($result['success']) {
            return response()->json([
                'message' => $result['message']
            ], 200);
        } else {
            $code = $result['code'] ?? 500;
            return response()->json([
                'error' => $result['error']
            ], $code);
        }
    }

    // Get File Info (optional - for completeness)
    public function getFile($courseId, $fileId)
    {
        // You can implement this if needed
        return response()->json(['error' => 'Not implemented'], 501);
    }
}

