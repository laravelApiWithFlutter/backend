<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\HasApiTokens;

class AuthController extends Controller
{
      public function register(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:user_profiles,email',
            'address'   => 'required|string',
            'password'  => 'required|min:6',
            'type'      => 'required|in:pro,prive',            
        ]);

         $user = UserProfile::create([
            'name'    => $validated['name'],
            'email'   => $validated['email'],
            'address' => $validated['address'],
            'password'=> Hash::make($validated['password']),
            'type'    => $validated['type'],
            
        ]);

        return response()->json([
            'message' => 'User registered successfully',
            'data'    => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email'     => 'required|email',
            'password'  => 'required',
        ]);

        $user = UserProfile::where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }

        // generate token (sanctum)
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token'   => $token,
            'user'    => $user
        ]);
    }

     public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    } 

    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
