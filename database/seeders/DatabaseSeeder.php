<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Create a test user without roles or permissions
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Assign roles and permissions and create admin users and normal users with their roles
        $this->call([
            RolesAndPermissionsSeeder::class,
            TestUserSeeder::class,
            NoteTypeSeeder::class,
            LanguageSeeder::class,
        ]);
    }
}
