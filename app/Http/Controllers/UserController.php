<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->where('id', '!=', auth()->id());

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('student_id', 'like', "%$search%");
            });
        }

        $users = $query->latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'student_id' => ['nullable', 'string', 'unique:users'],
            'role'       => ['required', 'in:admin,staff,user'],
            'password'   => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'student_id' => $request->student_id,
            'role'       => $request->role,
            'password'   => Hash::make($request->password),
        ]);

        return redirect()->route('admin.users.index')
                         ->with('success', 'User registered successfully.');
    }

    public function show(User $user)
    {
        $borrowings = $user->borrowings()->with('book')->latest()->paginate(10);
        return view('admin.users.show', compact('user', 'borrowings'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'student_id' => ['nullable', 'string', 'unique:users,student_id,' . $user->id],
            'role'       => ['required', 'in:admin,staff,user'],
        ]);

        $user->update($request->only('name', 'email', 'student_id', 'role'));

        if ($request->filled('password')) {
            $request->validate(['password' => ['confirmed', Rules\Password::defaults()]]);
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.users.index')
                         ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')
                         ->with('success', 'User deleted successfully.');
    }
}