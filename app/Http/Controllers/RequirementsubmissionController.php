<?php

namespace App\Http\Controllers;

use App\Services\RequirementSubmissionService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class RequirementSubmissionController extends Controller
{
    protected $service;

    public function __construct(RequirementSubmissionService $service)
    {
        $this->service = $service;
    }

    public function submit(Request $request, int $requirementID): JsonResponse
    {
             $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:5120',
        ]);

        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $file = $request->file('file');
        $studentID = auth()->id();

        $submission = $this->service->submitRequirement($requirementID, $studentID, $file);

        if (!$submission) {
            return response()->json(['error' => 'Submission already exists or failed'], 400);
        }

        return response()->json($submission, 201);
    }
    public function getSubmissions(int $requirementId): JsonResponse
    {
        $userId = auth('api')->user()->id;

        $result = $this->service->getSubmissionsByRequirement($requirementId, $userId);

        if (isset($result['error'])) {
            $status = $result['error'] === 'Unauthorized' ? 403 : 404;
            return response()->json(['error' => $result['error']], $status);
        }

        return response()->json(['submissions' => $result]);
    }
}
