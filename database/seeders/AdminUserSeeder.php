<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Проверяем, есть ли уже такой пользователь, чтобы не дублировать
        if (!User::where('email', 'admin@carshop.com')->exists()) {
            User::create([
                'name' => 'Главный Админ',
                'email' => 'admin@carshop.com', // Логин
                'password' => Hash::make('password123'), // Пароль
            ]);
        }
    }
}