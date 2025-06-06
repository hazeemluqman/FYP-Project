<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserAccountController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('accounts.index', compact('users'));
    }
    public function updateRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|in:manager,worker',
        ]);

        $user->role = $validated['role'];
        $user->save();

        return redirect()->route('accounts.index')->with('success', 'User role updated successfully.');
    }
}