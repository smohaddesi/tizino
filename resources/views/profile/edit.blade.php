@extends('layouts.app')

@section('title', 'پروفایل من')

@section('content')
    <div class="container py-4 py-lg-5">
        <h4 class="mb-4">پروفایل من</h4>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="mb-3">تصویر پروفایل</h6>

                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <div class="profile-avatar-preview">
                            @if ($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="عکس پروفایل">
                            @else
                                {{ \Illuminate\Support\Str::of($user->name)->substr(0, 1) }}
                            @endif
                        </div>

                        <div>
                            <input type="file" name="avatar" class="form-control form-control-sm" accept="image/*">
                            <small class="text-muted">فرمت تصویر، حداکثر ۲ مگابایت.</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="mb-3">مشخصات</h6>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">نام و نام خانوادگی</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">ایمیل</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">پایه‌ی تحصیلی</label>
                            <select name="grade_id" class="form-select">
                                <option value="">مشخص نشده</option>
                                @foreach ($grades as $id => $title)
                                    <option value="{{ $id }}" @selected(old('grade_id', $user->grade_id) == $id)>
                                        {{ $title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="mb-3">تغییر رمز عبور</h6>
                    <p class="text-muted small mb-3">اگه نمی‌خوای رمزت عوض بشه، این بخش رو خالی بذار.</p>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">رمز عبور فعلی</label>
                            <input type="password" name="current_password" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">رمز عبور جدید</label>
                            <input type="password" name="password" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">تکرار رمز عبور جدید</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-warning rounded-pill fw-bold px-4">
                ذخیره‌ی تغییرات
            </button>
        </form>
    </div>
@endsection

@push('styles')
    <style>
        .profile-avatar-preview {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: var(--tz-accent, #fd7e14);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            font-weight: 700;
            overflow: hidden;
        }

        .profile-avatar-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
@endpush
