@extends('layouts.guest')

@section('content')
    <h6 class="text-center text-muted mb-4">ثبت‌نام دانش‌آموز</h6>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">نام و نام خانوادگی</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}"
                   class="form-control @error('name') is-invalid @enderror" required autofocus>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">آدرس ایمیل</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   class="form-control @error('email') is-invalid @enderror" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">رمز عبور</label>
            <input id="password" type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror" required>
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">تکرار رمز عبور</label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                   class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">ثبت‌نام</button>

        <p class="text-center mt-3 mb-0">
            حساب کاربری داری؟
            <a href="{{ route('login') }}">وارد شو</a>
        </p>
    </form>
@endsection
