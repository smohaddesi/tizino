<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'سامانه آزمون تیزینو' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            background-color: #f8f9fa;
            font-family: Tahoma, Arial, sans-serif;
        }
        .auth-card {
            max-width: 420px;
            margin: 4rem auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="auth-card card shadow-sm">
            <div class="card-body p-4">
                <h5 class="text-center mb-1">سامانه آزمون تیزینو</h5>
                <h6 class="text-center text-muted mb-4">{{ $heading ?? '' }}</h6>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{ $slot ?? '' }}
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
