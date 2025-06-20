<?php

namespace Database\Seeders;

use App\Enums\PermissionsEnum;
use App\Enums\RolesEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $roles = $this->createRoles();

        // Create permissions
        $permissions = $this->createPermissions();

        // Assign permissions to roles
        $this->assignPermissionsToRoles($roles, $permissions);
    }

    /**
     * Create roles from RolesEnum.
     */
    private function createRoles(): array
    {
        $roles = [];
        foreach (RolesEnum::cases() as $roleEnum) {
            $roles[$roleEnum->name] = Role::firstOrCreate(['name' => $roleEnum->value]);
        }
        return $roles;
    }

    /**
     * Create permissions from PermissionsEnum.
     */
    private function createPermissions(): array
    {
        $permissions = [];
        foreach (PermissionsEnum::cases() as $permissionEnum) {
            $permissions[$permissionEnum->name] = Permission::firstOrCreate(['name' => $permissionEnum->value]);
        }
        return $permissions;
    }

    /**
     * Assign permissions to roles.
     */
    private function assignPermissionsToRoles(array $roles, array $permissions): void
    {
        // Admin role permissions
        $roles[RolesEnum::ADMIN->name]->syncPermissions([
            $permissions[PermissionsEnum::VIEW->name],
            $permissions[PermissionsEnum::CREATE->name],
            $permissions[PermissionsEnum::EDIT->name],
            $permissions[PermissionsEnum::DELETE->name],
        ]);

        // User role permissions
        $roles[RolesEnum::USER->name]->syncPermissions([
            $permissions[PermissionsEnum::NONE->name]
        ]);
    }
}
