<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin User', // يمكنك تغيير الاسم
            'email' => 'admin@example.com', // يمكنك تغيير البريد الإلكتروني
            'email_verified_at' => now(), // اختياري: لجعل البريد الإلكتروني مُحققًا
            'password' => Hash::make('password'), // اختر كلمة مرور قوية! هذا مجرد مثال.
            'role' => 'admin',
    ]);
}
}
