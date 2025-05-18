<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserAccountController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('accounts.index', compact('users'));
    }
}