<?php

namespace Database\Seeders;

use App\Enums\RolesEnum;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a default admin user
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ])->assignRole(RolesEnum::ADMIN->value);

        // Create additional admin users using a sequence as admin1, admin2, etc.
        User::factory()
            ->count(3)
            ->sequence(fn($sequence) => [
                'name' => 'Admin ' . ($sequence->index + 1),
                'email' => 'admin' . ($sequence->index + 1) . '@example.com',
            ])
            ->create()
            ->each(function ($user) {
                $user->assignRole(RolesEnum::ADMIN->value);
            });

        // Create a default normal user for testing with user role with sequence as test1, test2, etc.
        User::factory()
            ->count(3)
            ->sequence(fn($sequence) => [
                'name' => 'Test User ' . ($sequence->index + 1),
                'email' => 'test' . ($sequence->index + 1) . '@example.com',
            ])
            ->create()
            ->each(function ($user) {
                $user->assignRole(RolesEnum::USER->value);
            });
    }
}
