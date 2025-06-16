<?php

namespace App\Interfaces;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

interface UserServiceInterface
{
    public function deleteAccount(Request $request): JsonResponse;
    public function logout(Request $request): JsonResponse;
}