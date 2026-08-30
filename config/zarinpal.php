<?php

return [
    // مرچنت کد ۳۶ کاراکتری از پنل زرین‌پال (my.zarinpal.com)
    // در حالت sandbox می‌تونی یه رشته‌ی UUID دلخواه (مثلاً از uuidgenerator.net) اینجا بذاری
    'merchant_id' => env('ZARINPAL_MERCHANT_ID', ''),

    // واحد پول: IRT (تومان) یا IRR (ریال). قیمت پلن‌ها توی دیتابیس به تومانه، پس IRT درسته.
    'currency' => env('ZARINPAL_CURRENCY', 'IRT'),

    // حالت تست: true یعنی به‌جای درگاه واقعی از sandbox.zarinpal.com استفاده می‌شه
    // (هیچ پولی جابه‌جا نمی‌شه، فقط جریان کامل پرداخت رو شبیه‌سازی می‌کنه)
    'sandbox' => env('ZARINPAL_SANDBOX', false),
];
