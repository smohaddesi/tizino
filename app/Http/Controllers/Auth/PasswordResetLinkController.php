<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'وارد کردن ایمیل الزامی است.',
            'email.email' => 'ایمیل وارد شده معتبر نیست.',
        ]);

        // عمداً یه پیام یکسان نشون داده می‌شه چه ایمیل توی سامانه باشه چه نباشه،
        // تا مشخص نشه کدوم ایمیل‌ها توی سیستم ثبت‌نام کردن.
        Password::sendResetLink($request->only('email'));

        return back()->with(
            'status',
            'اگه این ایمیل توی سامانه ثبت شده باشه، لینک بازیابی رمز عبور براش ارسال می‌شه.'
        );
    }
}
