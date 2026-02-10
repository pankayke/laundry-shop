<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'phone'    => ['required', 'string', 'max:20', 'unique:users,phone'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'phone'    => $validated['phone'],
            'role'     => 'customer',
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        return redirect()->route('customer.dashboard');
    }
}
