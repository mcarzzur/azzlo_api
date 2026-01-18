<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate(
            [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'name_user' =>'required|string|max:255|unique:users',
            'profile_picture' => 'nullable|string',
            'date_birth' => 'required|date',
            'description' => 'string|max:255',
            'password' => 'required|string|min:6',

        ],
        [
            'email.unique' => 'El email ya existe',
            'name_user.unique' => 'El nombre de usuario ya existe'
        ]
        );

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'name_user' => $request->name_user,
            'profile_picture' => $request->profile_picture,
            'date_birth' => $request->date_birth,
            'description' => $request->description,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'name_user' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('name_user', $request->name_user)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'name_user' => ['Credenciales incorrectas.'],
            ]);
        }

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }
}
