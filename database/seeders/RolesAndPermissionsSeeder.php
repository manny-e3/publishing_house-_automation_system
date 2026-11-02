<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Permissions Matrix
        $permissions = [
            'view prospects',
            'manage manuscripts',
            'review content',
            'view invoices',
            'manage payments',
            'configure gateways',
            'view projects',
            'update project stages',
            'manage design',
            'upload production files',
            'view marketing analytics',
            'manage campaigns',
            'manage distribution',
            'manage inventory',
            'manage it infrastructure',
            'view audit logs',
            'author portal access',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        // 1. Super Admin
        $adminRole = Role::findOrCreate('admin');
        $adminRole->givePermissionTo(Permission::all());

        // 2. Acquisition Admin
        $acqRole = Role::findOrCreate(UserRole::AcquisitionAdmin->value);
        $acqRole->givePermissionTo(['view prospects', 'manage manuscripts', 'review content']);
        // Alias legacy role
        $legacyAcq = Role::findOrCreate('acquisitions');
        $legacyAcq->givePermissionTo(['view prospects', 'manage manuscripts', 'review content']);

        // 3. Editorial
        $editorialRole = Role::findOrCreate(UserRole::Editorial->value);
        $editorialRole->givePermissionTo(['view projects', 'update project stages', 'review content']);

        // 4. Finance
        $financeRole = Role::findOrCreate(UserRole::Finance->value);
        $financeRole->givePermissionTo(['view invoices', 'manage payments', 'configure gateways']);

        // 5. Design
        $designRole = Role::findOrCreate(UserRole::Design->value);
        $designRole->givePermissionTo(['view projects', 'manage design', 'upload production files']);

        // 6. Marketing
        $marketingRole = Role::findOrCreate(UserRole::Marketing->value);
        $marketingRole->givePermissionTo(['view prospects', 'view marketing analytics', 'manage campaigns']);

        // 7. IT
        $itRole = Role::findOrCreate(UserRole::It->value);
        $itRole->givePermissionTo(['manage it infrastructure', 'view audit logs', 'configure gateways']);

        // 8. Print & Distribution
        $distRole = Role::findOrCreate(UserRole::PrintDistribution->value);
        $distRole->givePermissionTo(['manage distribution', 'manage inventory', 'view projects']);

        // 9. Author / Agent
        $authorRole = Role::findOrCreate(UserRole::AuthorAgent->value);
        $authorRole->givePermissionTo(['author portal access']);
        $legacyProspect = Role::findOrCreate('prospect');
        $legacyProspect->givePermissionTo(['author portal access']);

        // Seed Core Team Users
        $this->createUser('Super Admin', 'admin@thecuratedarchive.com', 'password', $adminRole);
        $this->createUser('Acquisitions Head', 'acquisitions@thecuratedarchive.com', 'password', $acqRole);
        $this->createUser('Editor in Chief', 'editorial@thecuratedarchive.com', 'password', $editorialRole);
        $this->createUser('Finance Director', 'finance@thecuratedarchive.com', 'password', $financeRole);
        $this->createUser('Lead Designer', 'design@thecuratedarchive.com', 'password', $designRole);
        $this->createUser('Marketing Lead', 'marketing@thecuratedarchive.com', 'password', $marketingRole);
        $this->createUser('IT Operations Head', 'it@thecuratedarchive.com', 'password', $itRole);
        $this->createUser('Distribution Manager', 'distribution@thecuratedarchive.com', 'password', $distRole);
        $this->createUser('Jane Author', 'author@thecuratedarchive.com', 'password', $authorRole);
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

        $user->syncRoles([$role]);
    }
}
