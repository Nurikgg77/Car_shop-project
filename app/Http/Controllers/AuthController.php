<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Показать страницу входа (login form)
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Обработка данных входа
     */
    public function login(Request $request)
    {
        // 1. Валидация
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Попытка входа
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // ВАЖНО: После успешного входа перенаправляем в АДМИНКУ
            return redirect()->route('admin.dashboard')->with('success', 'Добро пожаловать в панель управления!');
        }

        // 3. Если ошибка
        return back()->withErrors([
            'email' => 'Неверный логин или пароль.',
        ])->onlyInput('email');
    }

    /**
     * Выход из системы
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // После выхода возвращаем на главную публичную страницу
        return redirect('/')->with('success', 'Вы вышли из системы.');
    }
}