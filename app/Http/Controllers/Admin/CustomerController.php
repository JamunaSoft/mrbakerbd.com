<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::role('User')
            ->select(['id', 'name', 'details', 'phone', 'email', 'photo', 'address', 'last_online', 'active'])
            ->latest('id')
            ->paginate(25)
            ->withQueryString();

        return view('backend.customer.index', compact('customers'));
    }

    public function create()
    {
       	return view('backend.customer.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'details' => 'nullable|string|max:1000',
            'phone' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'address' => 'nullable|string|max:1000',
            'active' => 'required|boolean',
        ]);

        $data['password'] = \Hash::make($data['password']);

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $filename = \Str::uuid() . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path('images/users'), $filename);
            $data['photo'] = 'images/users/' . $filename;
        }

        $user = User::create($data);

        $user->assignRole('User');

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Customer created successfully.');
    }

    public function edit($id)
    {
       $user = User::findOrFail($id);
      return view('backend.customer.edit', compact('user'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'details' => 'nullable|string|max:1000',
            'phone' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'address' => 'nullable|string|max:1000',
            'active' => 'required|boolean',
        ]);

        if ($request->filled('password')) {
            $data['password'] = \Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->photo && \File::exists(public_path($user->photo))) {
                \File::delete(public_path($user->photo));
            }
            $photo = $request->file('photo');
            $filename = \Str::uuid() . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path('images/users'), $filename);
            $data['photo'] = 'images/users/' . $filename;
        }

        $user->update($data);

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    public function destroy($id): RedirectResponse
    {
        $user = User::findOrFail($id);
        if ($user->photo && \File::exists(public_path($user->photo))) {
            \File::delete(public_path($user->photo));
        }

        $user->delete();

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Customer deleted successfully.');
    }

}
