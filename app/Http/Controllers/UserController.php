<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'is_active' => 'required|boolean',
            'expiry_date' => 'required|date',
            'role' => 'required|string|in:user,admin,api_user',
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['expiry_date'] = Carbon::parse($data['expiry_date']);

        User::create($data);

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        \Log::info($request->all());

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'is_active' => 'nullable|boolean',
            'expiry_date' => 'required|date',
            'role' => 'required|string|in:user,admin,api_user',
        ]);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $data['is_active'] = $request->has('is_active') ? true : false;
        $data['expiry_date'] = Carbon::parse($request->input('expiry_date'));

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }
}
