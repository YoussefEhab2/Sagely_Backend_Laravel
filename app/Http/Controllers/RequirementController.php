<?php

namespace App\Http\Controllers;

use App\Services\RequirementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequirementController extends Controller
{
    protected RequirementService $service;

    public function __construct(RequirementService $service)
    {
        $this->service = $service;
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'courseID'    => 'required|integer|exists:course,id',
        ]);

        $adminId = Auth::id();

        $result = $this->service->createRequirement($request->all(), $adminId);

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], $result['status']);
        }

        return response()->json(['requirement' => $result['data']], $result['status']);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'title'       => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $adminId = Auth::id();

        $result = $this->service->updateRequirement($id, $request->all(), $adminId);

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], $result['status']);
        }

        return response()->json(['requirement' => $result['data']], $result['status']);
    }

     public function deleteRequirement(int $id): JsonResponse
    {
        $result = $this->service->deleteRequirement($id);

        if ($result === true) {
            return response()->json(['message' => 'Requirement deleted successfully.'], 200);
        }

        return response()->json(['error' => $result], 403);
    }
    public function getRequirementsByCourse(int $courseId): JsonResponse
    {
        $requirements = $this->service->getRequirementsByCourse($courseId);

        if ($requirements->isEmpty()) {
            return response()->json(['message' => 'No requirements found for this course'], 404);
        }

        return response()->json($requirements, 200);
    }
}
