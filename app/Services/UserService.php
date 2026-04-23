<?php

namespace App\Services;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserWelcomeMail;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    /**
     * Get all users with their roles.
     */
    public function getUsers(): Collection
    {
        return User::with('roles')->get();
    }

    /**
     * Create a new user, assign a role, and send a welcome email.
     */
    public function createUser(array $data): User
    {
        $password = Str::random(12);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($password),
        ]);

        $user->assignRole($data['role']);

        Mail::to($user->email)->send(new UserWelcomeMail($user, $password));

        return $user;
    }

    /**
     * Update an existing user.
     */
    public function updateUser(User $user, array $data): void
    {
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        if (isset($data['password'])) {
            $user->update(['password' => Hash::make($data['password'])]);
        }

        $user->syncRoles([$data['role']]);
    }

    /**
     * Delete a user.
     */
    public function deleteUser(User $user, int $currentUserId): bool
    {
        if ($user->id === $currentUserId) {
            return false;
        }

        return (bool) $user->delete();
    }

    /**
     * Get all roles with user counts.
     */
    public function getRoles(): Collection
    {
        return Role::withCount('users')->get();
    }

    /**
     * Create a new role.
     */
    public function createRole(string $name): Role
    {
        return Role::create(['name' => $name]);
    }

    /**
     * Update a role and its permissions.
     */
    public function updateRole(Role $role, string $name, array $permissions): void
    {
        $role->update(['name' => $name]);
        $role->syncPermissions($permissions);
    }

    /**
     * Delete a role.
     */
    public function deleteRole(Role $role): bool
    {
        if (in_array($role->name, ['admin', 'author'])) {
            return false;
        }

        return (bool) $role->delete();
    }
}
