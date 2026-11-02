<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuditLog;
use App\Services\UserService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $users = $this->userService->getUsers();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'role' => ['required', 'exists:roles,name'],
        ]);

        $user = $this->userService->createUser($data);

        AuditLog::record('user_created', $user, [
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully and login details sent.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'role' => ['required', 'exists:roles,name'],
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['confirmed', Rules\Password::defaults()],
            ]);
            $data['password'] = $request->password;
        }

        $this->userService->updateUser($user, $data);

        AuditLog::record('user_updated', $user, [
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($this->userService->deleteUser($user, auth()->id())) {
            AuditLog::record('user_deleted', null, [
                'deleted_user_id' => $user->id,
                'deleted_user_name' => $user->name,
                'deleted_user_email' => $user->email,
            ]);

            return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
        }
        
        return redirect()->back()->with('error', 'You cannot delete yourself.');
    }

    /**
     * Display a specific user's complete audit trail and activity timeline.
     */
    public function activity(User $user)
    {
        $activities = AuditLog::where('user_id', $user->id)
            ->latest()
            ->paginate(25);

        $loginCount = AuditLog::where('user_id', $user->id)
            ->where('action', 'auth_login')
            ->count();

        $lastLogin = AuditLog::where('user_id', $user->id)
            ->where('action', 'auth_login')
            ->latest()
            ->first();

        return view('admin.users.activity', compact('user', 'activities', 'loginCount', 'lastLogin'));
    }
}
