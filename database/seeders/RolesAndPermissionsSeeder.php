<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            'view prospects',
            'manage manuscripts',
            'view invoices',
            'manage payments',
            'configure gateways',
            'view projects',
            'update project stages',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create Roles and Assign Permissions
        $adminRole = Role::findOrCreate('admin');
        $adminRole->givePermissionTo(Permission::all());

        $acquisitionsRole = Role::findOrCreate('acquisitions');
        $acquisitionsRole->givePermissionTo(['view prospects', 'manage manuscripts']);

        $financeRole = Role::findOrCreate('finance');
        $financeRole->givePermissionTo(['view invoices', 'manage payments', 'configure gateways']);

        $editorialRole = Role::findOrCreate('editorial');
        $editorialRole->givePermissionTo(['view projects', 'update project stages']);

        $prospectRole = Role::findOrCreate('prospect'); // Author Dashboard Access

        // Create Test Users
        $this->createUser('Admin', 'admin@thecuratedarchive.com', 'admin', $adminRole);
        $this->createUser('Acquisitions Head', 'acquisitions@thecuratedarchive.com', 'acquisitions', $acquisitionsRole);
        $this->createUser('Finance Manager', 'finance@thecuratedarchive.com', 'finance', $financeRole);
        $this->createUser('Editor in Chief', 'editorial@thecuratedarchive.com', 'editorial', $editorialRole);
    }

    private function createUser($name, $email, $password, $role)
    {
        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make($password),
            ]
        );

        $user->assignRole($role);
    }
}
