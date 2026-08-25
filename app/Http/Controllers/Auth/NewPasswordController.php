<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    public function create(Request $request, string $token): View
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ], [
            'email.required' => 'وارد کردن ایمیل الزامی است.',
            'email.email' => 'ایمیل وارد شده معتبر نیست.',
            'password.required' => 'وارد کردن رمز عبور جدید الزامی است.',
            'password.confirmed' => 'تکرار رمز عبور مطابقت نداره.',
            'password.min' => 'رمز عبور باید حداقل ۸ کاراکتر باشه.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user) use ($request) {
                $user->forceFill([
                    'password' => $request->password,
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()
                ->route('login')
                ->with('status', 'رمز عبورت با موفقیت تغییر کرد. حالا می‌تونی وارد بشی.');
        }

        throw ValidationException::withMessages([
            'email' => ['لینک بازیابی نامعتبر یا منقضی‌شده. دوباره درخواست بده.'],
        ]);
    }
}
