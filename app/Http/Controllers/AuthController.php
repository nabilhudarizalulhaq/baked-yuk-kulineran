<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Register
    public function fakereg()
    {
        return 'gg';
    }
    public function fakelog()
    {
        return 'fake';
    }
    public function register(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ]);

            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'role' => 'user'
            ]);

            return response()->json(['message' => 'User registered successfully'], 201);
        } catch (ValidationException $e) { // Menangkap kesalahan validasi
            return response()->json(['error' => 'Validation failed', 'messages' => $e->validator->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong. Please try again later.', 'message' => $e], 500);
        }
    }

    // Login
    public function login(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            $user = User::where('email', $validatedData['email'])->first();

            if (! $user || ! Hash::check($validatedData['password'], $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            $token = $user->createToken('mobile-app-token')->plainTextToken;

            return response()->json(['token' => $token, 'user' => $user], 200);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Validation failed', 'messages' => $e->validator->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong. Please try again later.', 'message' => $e], 500);
        }
    }

    // Logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
}
