@extends('layouts.guest')

@section('content')
    <h6 class="text-center text-muted mb-4">ورود به حساب کاربری</h6>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">آدرس ایمیل</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   class="form-control @error('email') is-invalid @enderror" required autofocus>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">رمز عبور</label>
            <input id="password" type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror" required>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="remember" class="form-check-input" id="remember">
            <label class="form-check-label" for="remember">مرا به خاطر بسپار</label>
        </div>

        <button type="submit" class="btn btn-primary w-100">ورود</button>

        <p class="text-center mt-3 mb-0">
            حساب کاربری نداری؟
            <a href="{{ route('register') }}">ثبت‌نام کن</a>
        </p>
    </form>
@endsection
