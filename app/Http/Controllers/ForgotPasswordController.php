<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    // 1. Fungsi untuk mengirim link reset ke email
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.exists' => 'Email ini tidak terdaftar di sistem kami.'
        ]);

        // Buat token acak sepanjang 64 karakter
        $token = Str::random(64);

        // Simpan token ke dalam database (tabel bawaan Laravel)
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => now()]
        );

        // Buat link yang akan mengarah ke aplikasi React Anda
        $frontendUrl = env('FRONTEND_URL', 'http://localhost:5173');
        $resetLink = $frontendUrl . '/reset-password?token=' . $token . '&email=' . urlencode($request->email);

        // Kirim Email (Teks Sederhana)
        Mail::raw("Halo!\n\nSeseorang meminta untuk mengubah password akun Anda di PT Trisena Rekainova Sinergi.\n\nSilakan klik link di bawah ini untuk mengatur password baru Anda:\n$resetLink\n\nJika Anda tidak merasa meminta perubahan password, abaikan email ini.", function ($message) use ($request) {
            $message->to($request->email)
                    ->subject('Reset Password - PT Trisena Rekainova');
        });

        return response()->json(['message' => 'Link reset password telah dikirim ke email Anda!']);
    }

    // 2. Fungsi untuk menyimpan password baru
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed', // Harus ada password_confirmation
        ]);

        // Cek apakah tokennya valid dan cocok dengan email
        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$record) {
            return response()->json(['message' => 'Token tidak valid atau sudah kadaluarsa.'], 400);
        }

        // Ganti password user (Admin atau Customer sama saja)
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Hapus token agar tidak bisa dipakai 2x
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json(['message' => 'Password berhasil diubah. Silakan login dengan password baru.']);
    }
}