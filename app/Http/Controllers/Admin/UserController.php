<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\StoreUserRequest;
use App\Http\Requests\Admin\User\UpdateUserRequest;
use App\Models\User;
use App\Models\UserPrivilege;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService
    ) {
    }

    public function index(): View
    {
        $users = $this->userService->getUsersByRoles(['Admin', 'Manager']);
        return view('backend.user.index', compact('users'));
    }

    public function create(): View
    {
        $roles = Role::all();
        return view('backend.user.create', compact('roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'details' => 'nullable|string|max:1000',
            'phone' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'address' => 'nullable|string|max:1000',
            'role' => 'required|exists:roles,name',
            'active' => 'required|boolean',
        ]);

        $data['password'] = Hash::make($data['password']);

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $filename = Str::uuid() . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path('images/users'), $filename);
            $data['photo'] = 'images/users/' . $filename;
        }

        $user = User::create($data);

        if ($request->filled('role')) {
            $user->assignRole($request->input('role'));
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user): View
    {
        $roles = Role::all();
        return view('backend.user.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'details' => 'nullable|string|max:1000',
            'phone' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'address' => 'nullable|string|max:1000',
            'role' => 'required|exists:roles,name',
            'active' => 'required|boolean',
        ]);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->photo && File::exists(public_path($user->photo))) {
                File::delete(public_path($user->photo));
            }
            $photo = $request->file('photo');
            $filename = Str::uuid() . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path('images/users'), $filename);
            $data['photo'] = 'images/users/' . $filename;
        }

        $user->update($data);

        if ($request->filled('role')) {
            $user->syncRoles([$request->input('role')]);
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->photo && File::exists(public_path($user->photo))) {
            File::delete(public_path($user->photo));
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        $user->update(['is_active' => !$user->is_active]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User status updated successfully.');
    }
}
