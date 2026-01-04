<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $user = User::create($validatedData);
        return response()->json($user);
    }
    public function login(Request $request) {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $user = User::where('email', $validatedData['email'])->first();
        if(!$user)
        {
            throw ValidationException::withMessages([
                'email' => 'The credential are incorrect'
            ]);
        }
        if(!Hash::check($validatedData['password'], $user->password)){
            throw ValidationException::withMessages([
                'email' => 'The credential are incorrect'
            ]);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'email' => $validatedData['email'],
            'password' => $validatedData['password']
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged Out Successfully',
        ]);
    }
}
