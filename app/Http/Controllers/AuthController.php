<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    private const AVATARS = [
        'driver1.png',
        'driver2.png',
        'driver3.png',
    ];

    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->except('password'))
                ->withErrors(['email' => 'Неверный e-mail или пароль.']);
        }

        $request->session()->regenerate();

        return redirect()->intended(
            Auth::user()->isInstructor() ? route('cabinet.index') : route('home')
        );
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[А-ЯЁA-Z][а-яёa-z]+(?:-[А-ЯЁA-Z][а-яёa-z]+)?(?:\s+[А-ЯЁA-Z][а-яёa-z]+(?:-[А-ЯЁA-Z][а-яёa-z]+)?)+$/u',
            ],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'regex:/^(?:\+7|7|8)[\s\-]?\(?\d{3}\)?[\s\-]?\d{3}[\s\-]?\d{2}[\s\-]?\d{2}$/'],
            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
                'regex:/[A-ZА-ЯЁ]/u',
                'regex:/\d/',
            ],
        ], [
            'name.regex' => 'ФИО должно содержать минимум два слова, и каждое слово должно начинаться с большой буквы.',
            'email.email' => 'Введите корректный e-mail адрес.',
            'phone.regex' => 'Введите корректный российский номер телефона.',
            'password.min' => 'Пароль должен содержать минимум 6 символов.',
            'password.regex' => 'Пароль должен содержать минимум одну заглавную букву и одну цифру.',
            'password.confirmed' => 'Подтверждение пароля не совпадает.',
        ]);

        $data['role'] = 'visitor';
        $data['avatar'] = self::AVATARS[array_rand(self::AVATARS)];

        $user = User::create($data);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')
            ->with('status', 'Регистрация прошла успешно.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('status', 'Вы вышли из системы.');
    }
}
