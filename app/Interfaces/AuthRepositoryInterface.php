<?php

namespace App\Interfaces;

use App\Http\Requests\AppleLoginRequest;
use App\Http\Requests\GuestLoginRequest;
use Illuminate\Http\JsonResponse;

interface AuthRepositoryInterface
{
    public function guestLogin(GuestLoginRequest $request): JsonResponse;
    public function appleLogin(AppleLoginRequest $request): JsonResponse;
}