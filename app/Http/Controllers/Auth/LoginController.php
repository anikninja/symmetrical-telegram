<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppleLoginRequest;
use App\Http\Requests\GuestLoginRequest;
use App\Interfaces\AuthRepositoryInterface;

class LoginController extends Controller
{
    public function __construct(
        private AuthRepositoryInterface $authService
    ) {}

    public function guestLogin(GuestLoginRequest $request)
    {
        return $this->authService->guestLogin($request);
    }

    public function appleLogin(AppleLoginRequest $request)
    {
        return $this->authService->appleLogin($request);
    }
}