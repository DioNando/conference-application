<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'delete users']);

        Permission::create(['name' => 'view roles']);
        Permission::create(['name' => 'create roles']);
        Permission::create(['name' => 'edit roles']);
        Permission::create(['name' => 'delete roles']);

        Permission::create(['name' => 'view dashboard']);
        Permission::create(['name' => 'manage settings']);

        // Create roles and assign permissions
        $adminRole = Role::create(['name' => UserRole::ADMIN->value]);
        $adminRole->givePermissionTo(Permission::all());

        $managerRole = Role::create(['name' => UserRole::MANAGER->value]);
        $managerRole->givePermissionTo([
            'view users', 'create users', 'edit users',
            'view roles',
            'view dashboard',
            'manage settings',
        ]);

        $userRole = Role::create(['name' => UserRole::USER->value]);
        $userRole->givePermissionTo([
            'view dashboard',
        ]);

        $guestRole = Role::create(['name' => UserRole::GUEST->value]);
        // Guest role has no permissions by default
    }
}
