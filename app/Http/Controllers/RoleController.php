<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $roles = $this->userService->getRoles();
        return view('admin.roles.index', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'unique:roles,name'],
        ]);

        $this->userService->createRole($request->name);

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => ['required', 'string', 'unique:roles,name,'.$role->id],
            'permissions' => ['nullable', 'array'],
        ]);

        $this->userService->updateRole($role, $request->name, $request->permissions ?? []);

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
    }
    
    public function destroy(Role $role)
    {
        if ($this->userService->deleteRole($role)) {
            return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully.');
        }
        
        return redirect()->back()->with('error', 'Core roles cannot be deleted.');
    }
}
