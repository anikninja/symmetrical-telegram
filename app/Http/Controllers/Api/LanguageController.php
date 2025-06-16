<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LanguageController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $languages = Language::where('status', 'active')->get();
        
        return response()->json($languages);
    }
}
