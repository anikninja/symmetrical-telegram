<?php

namespace App\Services;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Interfaces\UserServiceInterface;
use App\Interfaces\UserRepositoryInterface;

class UserService implements UserServiceInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function deleteAccount(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $user->tokens()->delete();
            $user->delete();

            // $this->userRepository->revokeTokens($user);
            // $this->userRepository->deleteUser($user);

            return ApiResponse::response(
                true,
                'Account deleted successfully.'
            );
        } catch (\Exception $e) {
            return ApiResponse::serverError('Failed to delete account');
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $user->token()->revoke();
            // $this->userRepository->revokeCurrentToken($request->user());

            return ApiResponse::response(
                true,
                'Successfully logged out.'
            );
        } catch (\Exception $e) {
            return ApiResponse::serverError('Failed to logout');
        }
    }
}