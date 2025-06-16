<?php

namespace App\Repositories;

use App\Models\User;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\AppleLoginRequest;
use App\Http\Requests\GuestLoginRequest;
use App\Interfaces\AuthRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Log;

class AuthRepository implements AuthRepositoryInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function guestLogin(GuestLoginRequest $request): JsonResponse
    {
        try {
            $user = $this->userRepository->findByDeviceToken($request->device_token);

            if (!$user) {
                $user = $this->userRepository->createGuest([
                    'name' => $request->validated()['name'],
                    'device_token' => $request->validated()['device_token'],
                ]);
                $message = 'New guest user created and logged in successfully.';
            } else {
                $message = 'Guest login successful.';
            }

            $token = $user->createToken('guest-token')->accessToken;

            return ApiResponse::response(
                true,
                $message,
                [
                    'user' => $user,
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                ]
            );
        } catch (\Exception $e) {
            Log::error('Guest login failed: ' . $e->getMessage(), ['exception' => $e]);
            return ApiResponse::serverError('Failed to process guest login');
        }
    }

    public function appleLogin(AppleLoginRequest $request): JsonResponse
    {
        try {
            // Try to find existing user by auth_id first
            if ($user = $this->userRepository->findByAuthId($request->auth_id)) {
                return $this->handleExistingAuthIdUser($request, $user);
            }

            // Check for guest user to upgrade
            if ($user = $this->userRepository->findByDeviceToken($request->device_token)) {
                if ($user->is_guest) {
                    return $this->handleGuestUserUpgrade($request, $user);
                }
            }

            // Check for email-based user to link
            if ($request->filled('email')) {
                if ($user = $this->userRepository->findByEmail($request->email)) {
                    return $this->handleEmailUserLink($request, $user);
                }
            }

            // Validate no conflicts before creating new user
            if ($errorResponse = $this->validateAppleUserCreation($request)) {
                return $errorResponse;
            }

            // Create new Apple-authenticated user
            return $this->createNewAppleUser($request);
        } catch (\Exception $e) {
            Log::error('Apple login failed: ' . $e->getMessage(), ['exception' => $e]);
            return ApiResponse::serverError('Failed to process Apple login');
        }
    }

    protected function handleExistingAuthIdUser(AppleLoginRequest $request, \App\Models\User $user): JsonResponse
    {
        $updateData = [
            'device_token' => $request->device_token,
            'is_guest' => false,
        ];

        if ($request->filled('name') && $user->name !== $request->name) {
            $updateData['name'] = $request->name;
        }

        if ($request->filled('email') && $user->email !== $request->email) {
            if ($this->userRepository->emailExistsForOtherUser($request->email, $user->id)) {
                return ApiResponse::response(
                    false,
                    'This email address is already associated with another account.',
                    null,
                    'email_conflict',
                    409
                );
            }
            $updateData['email'] = $request->email;
        }

        $user = $this->userRepository->update($user, $updateData);
        $token = $user->createToken('apple-login-token')->accessToken;

        return ApiResponse::response(
            true,
            'Existing user logged in successfully via Apple.',
            [
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]
        );
    }

    protected function handleGuestUserUpgrade(AppleLoginRequest $request, \App\Models\User $user): JsonResponse
    {
        $updateData = [
            'auth_id' => $request->auth_id,
            'is_guest' => false,
        ];

        if ($request->filled('name')) {
            $updateData['name'] = $request->name;
        }

        if ($request->filled('email')) {
            $emailConflict = $this->userRepository->emailExistsForOtherUser($request->email, $user->id);
            if ($emailConflict) {
                return ApiResponse::response(
                    false,
                    'This email address is already associated with another authenticated account.',
                    null,
                    'email_conflict',
                    409
                );
            }
            $updateData['email'] = $request->email;
        }

        $user = $this->userRepository->update($user, $updateData);
        $token = $user->createToken('apple-linked-guest-token')->accessToken;

        return ApiResponse::response(
            true,
            'Guest account successfully linked with Apple ID.',
            [
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]
        );
    }

    protected function handleEmailUserLink(AppleLoginRequest $request, User $user): JsonResponse
    {
        if ($user->auth_id) {
            return ApiResponse::response(
                false,
                'This email is already linked to a different Apple ID.',
                null,
                'apple_id_conflict',
                409
            );
        }

        $updateData = [
            'auth_id' => $request->auth_id,
            'device_token' => $request->device_token,
            'is_guest' => false,
        ];

        if ($request->filled('name')) {
            $updateData['name'] = $request->name;
        }

        $user = $this->userRepository->update($user, $updateData);
        $token = $user->createToken('apple-linked-email-token')->accessToken;

        return ApiResponse::response(
            true,
            'Existing account successfully linked with Apple ID via email.',
            [
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]
        );
    }

    protected function validateAppleUserCreation(AppleLoginRequest $request): ?JsonResponse
    {
        if ($request->filled('email') && $this->userRepository->emailHasOtherAuthId($request->email)) {
            return ApiResponse::response(
                false,
                'This email is already in use by another Apple account.',
                null,
                'email_conflict',
                409
            );
        }

        if ($this->userRepository->deviceTokenHasOtherAuthId($request->device_token, $request->auth_id)) {
            return ApiResponse::response(
                false,
                'This device is already associated with a different Apple ID.',
                null,
                'device_conflict',
                409
            );
        }

        return null;
    }

    protected function createNewAppleUser(AppleLoginRequest $request): JsonResponse
    {
        $user = $this->userRepository->createAppleUser([
            'name' => $request->name,
            'email' => $request->email,
            'auth_id' => $request->auth_id,
            'device_token' => $request->device_token,
        ]);

        $token = $user->createToken('apple-new-user-token')->accessToken;

        return ApiResponse::response(
            true,
            'New user created and logged in successfully via Apple.',
            [
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]
        );
    }
}
