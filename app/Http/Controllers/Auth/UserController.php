<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\UserServiceInterface;

class UserController extends Controller
{
    public function __construct(
        private UserServiceInterface $userService
    ) {
    }

    public function deleteAccount(Request $request)
    {
        return $this->userService->deleteAccount($request);
    }

    public function logout(Request $request)
    {
        return $this->userService->logout($request);
    }
}