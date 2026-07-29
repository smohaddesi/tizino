<?php

return [
    'fields' => [
        'search' => 'جستجو',
        'reset' => 'بازنشانی',
        'filter' => 'فیلتر',
        'bulk_select' => 'انتخاب گروهی',
        'select_all' => 'انتخاب همه',
        'deselect_all' => 'لغو انتخاب همه',
        'reorder' => 'مرتب‌سازی',
    ],
    
    'buttons' => [
        'create' => 'ایجاد',
        'save' => 'ذخیره',
        'edit' => 'ویرایش',
        'delete' => 'حذف',
        'cancel' => 'انصراف',
        'close' => 'بستن',
        'confirm' => 'تأیید',
        'view' => 'مشاهده',
        'preview' => 'پیش‌نمایش',
        'attach' => 'افزودن',
        'detach' => 'حذف',
        'replicate' => 'تکثیر',
        'restore' => 'بازیابی',
        'force_delete' => 'حذف کامل',
        'import' => 'ورود',
        'export' => 'خروج',
    ],
    
    'messages' => [
        'created' => 'با موفقیت ایجاد شد.',
        'updated' => 'با موفقیت به‌روزرسانی شد.',
        'deleted' => 'با موفقیت حذف شد.',
        'restored' => 'با موفقیت بازیابی شد.',
        'reordered' => 'با موفقیت مرتب‌سازی شد.',
        'no_records' => 'هیچ رکوردی یافت نشد.',
        'no_results' => 'نتیجه‌ای یافت نشد.',
        'confirm_delete' => 'آیا از حذف این آیتم مطمئن هستید؟',
        'confirm_bulk_delete' => 'آیا از حذف موارد انتخاب‌شده مطمئن هستید؟',
        'delete_forever' => 'این عملیات غیرقابل بازگشت است.',
    ],
    
    'tables' => [
        'empty' => 'هیچ رکوردی یافت نشد.',
        'filters' => [
            'label' => 'فیلترها',
            'clear' => 'پاک کردن همه فیلترها',
            'apply' => 'اعمال فیلتر',
            'remove' => 'حذف فیلتر',
            'remove_all' => 'حذف همه فیلترها',
        ],
        'columns' => [
            'actions' => 'عملیات',
        ],
    ],
    
    'forms' => [
        'components' => [
            'field' => [
                'add' => 'افزودن',
                'remove' => 'حذف',
                'create' => 'ایجاد',
                'edit' => 'ویرایش',
                'view' => 'مشاهده',
                'search' => 'جستجو',
                'clear' => 'پاک کردن',
                'select' => 'انتخاب',
                'deselect' => 'لغو انتخاب',
            ],
            'repeater' => [
                'add' => 'افزودن آیتم',
                'remove' => 'حذف آیتم',
            ],
            'select' => [
                'loading' => 'در حال بارگذاری...',
                'no_options' => 'گزینه‌ای وجود ندارد',
                'search_prompt' => 'برای جستجو تایپ کنید...',
            ],
        ],
    ],
    
    'pages' => [
        'dashboard' => [
            'title' => 'داشبورد',
        ],
        'login' => [
            'title' => 'ورود',
            'heading' => 'ورود به سیستم',
            'subheading' => 'برای ورود اطلاعات خود را وارد کنید',
            'fields' => [
                'email' => 'ایمیل',
                'password' => 'رمز عبور',
                'remember' => 'مرا به خاطر بسپار',
            ],
            'buttons' => [
                'submit' => 'ورود',
            ],
        ],
        'profile' => [
            'title' => 'پروفایل',
            'heading' => 'پروفایل کاربری',
            'subheading' => 'اطلاعات خود را ویرایش کنید',
        ],
        'password' => [
            'title' => 'تغییر رمز عبور',
            'heading' => 'تغییر رمز عبور',
            'subheading' => 'برای امنیت بیشتر، رمز عبور خود را به‌روز کنید',
            'fields' => [
                'current' => 'رمز عبور فعلی',
                'new' => 'رمز عبور جدید',
                'confirm' => 'تأیید رمز عبور جدید',
            ],
        ],
    ],
];