<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }
    public function edit($id)
{
    $user = User::findOrFail($id);
    return view('profile.edit', compact('user'));
}

public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'nullable|string|max:20',
        'role' => 'required|string|max:50', // Add validation for role
    ]);

    $user->update($validated);

    return redirect()->route('profile.index')
        ->with('success', 'Profile updated successfully');
}
}