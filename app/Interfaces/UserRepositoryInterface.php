<?php

namespace App\Interfaces;

use App\Models\User;

interface UserRepositoryInterface
{
    public function findByDeviceToken(string $deviceToken): ?User;
    public function findByAuthId(string $authId): ?User;
    public function findByEmail(string $email): ?User;
    public function createGuest(array $data): User;
    public function createAppleUser(array $data): User;
    public function update(User $user, array $data): User;
    public function emailExistsForOtherUser(string $email, int $excludeUserId): bool;
    public function deviceTokenHasOtherAuthId(string $deviceToken, string $authId): bool;
    public function emailHasOtherAuthId(string $email): bool;
    public function deleteUser(User $user): bool;
    public function revokeTokens(User $user): void;
    public function revokeCurrentToken(User $user): void;
}
