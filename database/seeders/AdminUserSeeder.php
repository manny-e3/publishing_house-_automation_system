<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure Roles exist (just in case they weren't seeded yet)
        $roles = ['admin', 'acquisitions', 'finance', 'editorial'];
        foreach ($roles as $roleName) {
            Role::findOrCreate($roleName);
        }

        $admins = [
            [
                'name' => 'System Administrator',
                'email' => 'aboajahemmanuel@gmail.com',
                'password' => 'admin', // Change on production
                'role' => 'admin'
            ],
            [
                'name' => 'Acquisitions Manager',
                'email' => 'ae3techngltd@gmail.com',
                'password' => 'admin',
                'role' => 'acquisitions'
            ],
            [
                'name' => 'Finance Lead',
                'email' => 'denverimousines@gmail.com',
                'password' => 'admin',
                'role' => 'finance'
            ],
            [
                'name' => 'Editor in Chief',
                'email' => 'travaiq@gmail.com',
                'password' => 'admin',
                'role' => 'editorial'
            ]
        ];

        foreach ($admins as $adminData) {
            $user = User::updateOrCreate(
                ['email' => $adminData['email']],
                [
                    'name' => $adminData['name'],
                    'password' => Hash::make($adminData['password']),
                ]
            );

            $user->assignRole($adminData['role']);
        }
    }
}
