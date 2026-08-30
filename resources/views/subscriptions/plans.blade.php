<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>اشتراک تیزینو</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">
    <div class="container py-5">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">اشتراک تیزینو</h4>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">بازگشت به داشبورد</a>
        </div>

        <p class="text-muted mb-4">
            با تهیه‌ی اشتراک، به کل بانک سؤال و سیستم آزمون دسترسی کامل خواهید داشت.
        </p>

        @if (session('success'))
            <div class="alert alert-success text-center">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger text-center">{{ session('error') }}</div>
        @endif

        @if ($activeSubscription)
            <div class="alert alert-info text-center mb-5">
                شما هم‌اکنون یک اشتراک فعال دارید که تا تاریخ
                <strong>{{ $activeSubscription->ends_at->format('Y/m/d') }}</strong>
                اعتبار دارد.
            </div>
        @endif

        <div class="row justify-content-center g-4">
            @forelse ($plans as $plan)
                <div class="col-md-5 col-lg-4">
                    <div class="card h-100 shadow-sm border-0 text-center">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold">{{ $plan->title }}</h5>

                            <div class="my-3">
                                <span class="display-6 fw-bold">{{ number_format($plan->price) }}</span>
                                <span class="text-muted">تومان</span>
                            </div>

                            @if ($plan->description)
                                <p class="text-muted flex-grow-1">{{ $plan->description }}</p>
                            @endif

                            <form action="{{ route('subscriptions.checkout', $plan) }}" method="POST" class="mt-3">
                                @csrf
                                <button type="submit" class="btn btn-warning w-100 rounded-pill fw-bold">
                                    خرید این اشتراک
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-muted">در حال حاضر پلن فعالی برای نمایش وجود ندارد.</p>
            @endforelse
        </div>

    </div>
</body>
</html>
