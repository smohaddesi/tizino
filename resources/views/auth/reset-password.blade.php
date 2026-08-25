@extends('layouts.guest')

@section('content')
    <h6 class="text-center text-muted mb-4">تعیین رمز عبور جدید</h6>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="mb-3">
            <label for="email" class="form-label">آدرس ایمیل</label>
            <input id="email" type="email" name="email" value="{{ old('email', $email) }}"
                   class="form-control @error('email') is-invalid @enderror" required autofocus>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">رمز عبور جدید</label>
            <input id="password" type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror" required>
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">تکرار رمز عبور جدید</label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                   class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">تغییر رمز عبور</button>
    </form>
@endsection
