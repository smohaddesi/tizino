<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>داشبورد دانش‌آموز</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">خوش آمدی، {{ auth()->user()->name }} 👋</h4>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm">خروج</button>
            </form>
        </div>

        <div class="alert alert-info">
            این صفحه فعلاً یک نمونه‌ی اولیه است. بخش نمایش آزمون‌های در دسترس و شروع آزمون
            به‌زودی به همین‌جا اضافه خواهد شد.
        </div>
    </div>
</body>
</html>
