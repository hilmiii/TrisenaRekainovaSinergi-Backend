<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Membuat akun Admin Master
        User::create([
            'name' => 'Andy Fajar Anggoro',
            'email' => 'andyfajar76@gmail.com',
            'password' => Hash::make('Anggoro_76'), // Password admin
            'role' => 'admin', // <-- Kunci pembedanya ada di sini
            'phone' => '081298229897',
            'company' => 'PT. Trisena Rekainova Sinergi',
            'address' => 'Kantor Pusat Trisena'
        ]);
    }
}