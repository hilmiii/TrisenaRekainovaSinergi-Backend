<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Fungsi untuk Mendaftar
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed', // Pastikan ada password_confirmation di frontend
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user' // Default role adalah user biasa
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Registrasi berhasil',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    // Fungsi untuk Login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Email atau Password salah.'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'user' => $user,
            'token' => $token
        ]);
    }

    // Fungsi Edit Profil
   // Fungsi untuk Update Profil
    public function updateProfile(Request $request)
    {
        // 1. Tarik user secara eksplisit menggunakan sistem Sanctum
        $user = auth('sanctum')->user();

        // 2. Safety Check: Cegah Error 500 jika token terlepas
        if (!$user) {
            return response()->json([
                'message' => 'Sesi tidak valid. Silakan login ulang.'
            ], 401);
        }

        // 3. Validasi input dari React
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        // 4. Lakukan update data ke MySQL
        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'company' => $request->company,
            'address' => $request->address,
        ]);

        return response()->json([
            'message' => 'Profil berhasil diperbarui',
            'user' => $user
        ]);
    }
    // Fungsi untuk Logout
    public function logout(Request $request)
    {
        // Menghapus token yang sedang digunakan
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Berhasil logout']);
    }
}