<?php

namespace App\Http\Controllers;

use App\Services\DownloadableFileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;
class DownloadableFileController extends Controller
{
    protected $service;

    public function __construct(DownloadableFileService $service)
    {
        $this->service = $service;
    }

   public function uploadFile(Request $request, $courseId)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'details' => $validator->errors()
            ], 422);
        }

        // Get authenticated admin ID
        $adminId =auth('api')->user()->id;

        // Call service to handle file upload
        $result = $this->service->uploadFile(
            $courseId,
            $adminId,
            $request->only(['name', 'description']),
            $request->file('file')
        );

        // Return response
        if ($result['success']) {
            return response()->json([
                'message' => $result['message'],
                'file' => $result['file']
            ], 201);
        } else {
            $code = $result['code'] ?? 500;
            return response()->json([
                'error' => $result['error']
            ], $code);
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

    


     public function downloadFile($fileId)
{
    
    $userId = auth('api')->user()->id;

    
    $result = $this->service->downloadFile($fileId, $userId);

   
    if ($result['success']) {
        return response()->json([
            'success' => true,
            'file_url' => $result['file_url'],
            'file_name' => $result['file_name'],
            'file' => $result['file']
        ], 200);
    } else {
        $code = $result['code'] ?? 500;
        return response()->json([
            'success' => false,
            'error' => $result['error']
        ], $code);
    }
}


public function getByCourse(int $courseId)
    {
        $user = auth('api')->user();

        $result = $this->service->getFilesByCourse($courseId, $user->id);

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], $result['status']);
        }

        return response()->json([
            'message' => 'Files retrieved successfully',
            'files'   => $result['data']
        ], 200);
    }
}

