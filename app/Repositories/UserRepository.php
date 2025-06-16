<?php

namespace App\Repositories;

use App\Models\User;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Str;


class UserRepository implements UserRepositoryInterface
{
    public function findByDeviceToken(string $deviceToken): ?User
    {
        return User::where('device_token', $deviceToken)->first();
    }

    public function findByAuthId(string $authId): ?User
    {
        return User::where('auth_id', $authId)->first();
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function createGuest(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'device_token' => $data['device_token'],
            'is_guest' => true,
            'password' => bcrypt(Str::random(40)),
        ]);
    }

    public function createAppleUser(array $data): User
    {
        return User::create([
            'name' => $data['name'] ?? 'User',
            'email' => $data['email'],
            'auth_id' => $data['auth_id'],
            'device_token' => $data['device_token'],
            'is_guest' => false,
            'password' => null,
        ]);
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);
        return $user->fresh();
    }

    public function emailExistsForOtherUser(string $email, int $excludeUserId): bool
    {
        return User::where('email', $email)
            ->where('id', '!=', $excludeUserId)
            ->exists();
    }

    public function deviceTokenHasOtherAuthId(string $deviceToken, string $authId): bool
    {
        return User::where('device_token', $deviceToken)
            ->whereNotNull('auth_id')
            ->where('auth_id', '!=', $authId)
            ->exists();
    }

    public function emailHasOtherAuthId(string $email): bool
    {
        return User::where('email', $email)
            ->whereNotNull('auth_id')
            ->exists();
    }

    public function deleteUser(User $user): bool
    {
        return $user->delete();
    }

    public function revokeTokens(User $user): void
    {
        $user->tokens()->delete();
    }

    public function revokeCurrentToken(User $user): void
    {
        $user->token()->revoke();
    }
}
