@extends('layouts.guest')

@section('content')
    <h6 class="text-center text-muted mb-4">فراموشی رمز عبور</h6>

    <p class="text-muted small mb-3">ایمیلت رو وارد کن؛ اگه توی سامانه ثبت شده باشه، لینک بازیابی رمز عبور براش ارسال می‌شه.</p>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">آدرس ایمیل</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   class="form-control @error('email') is-invalid @enderror" required autofocus>
        </div>

        <button type="submit" class="btn btn-primary w-100">ارسال لینک بازیابی</button>

        <p class="text-center mt-3 mb-0">
            <a href="{{ route('login') }}">بازگشت به ورود</a>
        </p>
    </form>
@endsection
