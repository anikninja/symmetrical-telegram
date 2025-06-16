<?php

namespace App\Http\Controllers\Api;

use App\Models\NoteType;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;

class NoteTypeController extends Controller
{
    public function index(): JsonResponse
    {
        $noteTypes = NoteType::where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'status']);

        return ApiResponse::response(true, 'Note types retrieved successfully.', $noteTypes);
    }
}
