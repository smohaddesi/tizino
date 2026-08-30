<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ZarinPalService
{
    /**
     * دامنه‌ی پایه بر اساس حالت عادی/تست (sandbox).
     */
    private function baseDomain(): string
    {
        return config('zarinpal.sandbox', false)
            ? 'sandbox.zarinpal.com'
            : 'api.zarinpal.com';
    }

    /**
     * دامنه‌ی مخصوص صفحه‌ی نهایی پرداخت (StartPay).
     * توجه: توی حالت عادی این دامنه با دامنه‌ی request/verify فرق داره (www به‌جای api)،
     * ولی توی sandbox هر سه یکی هستن.
     */
    private function startPayDomain(): string
    {
        return config('zarinpal.sandbox', false)
            ? 'sandbox.zarinpal.com'
            : 'www.zarinpal.com';
    }

    private function requestUrl(): string
    {
        return "https://{$this->baseDomain()}/pg/v4/payment/request.json";
    }

    private function verifyUrl(): string
    {
        return "https://{$this->baseDomain()}/pg/v4/payment/verify.json";
    }

    /**
     * ارسال درخواست پرداخت به زرین‌پال و دریافت Authority.
     *
     * @return array{success: bool, authority?: string, message?: string}
     */
    public function request(int $amount, string $description, string $callbackUrl, ?string $mobile = null, ?string $email = null): array
    {
        $payload = [
            'merchant_id' => config('zarinpal.merchant_id'),
            'currency' => config('zarinpal.currency', 'IRT'),
            'amount' => $amount,
            'callback_url' => $callbackUrl,
            'description' => $description,
        ];

        $metadata = array_filter([
            'mobile' => $mobile,
            'email' => $email,
        ]);

        if (! empty($metadata)) {
            $payload['metadata'] = $metadata;
        }

        $response = Http::asJson()->acceptJson()->post($this->requestUrl(), $payload);

        $data = $response->json('data');

        if (! $response->successful() || empty($data) || ($data['code'] ?? null) !== 100) {
            Log::warning('ZarinPal payment request failed', [
                'sandbox' => config('zarinpal.sandbox', false),
                'payload' => $payload,
                'response' => $response->json(),
            ]);

            return [
                'success' => false,
                'message' => $data['message'] ?? 'خطا در اتصال به درگاه پرداخت. لطفاً بعداً دوباره تلاش کنید.',
            ];
        }

        return [
            'success' => true,
            'authority' => $data['authority'],
        ];
    }

    /**
     * آدرس نهایی که کاربر باید بهش ریدایرکت بشه تا وارد درگاه پرداخت بشه.
     */
    public function startPayUrl(string $authority): string
    {
        return "https://{$this->startPayDomain()}/pg/StartPay/{$authority}";
    }

    /**
     * تأیید نهایی تراکنش بعد از بازگشت کاربر از درگاه.
     *
     * @return array{success: bool, ref_id?: string, already_verified?: bool, message?: string}
     */
    public function verify(int $amount, string $authority): array
    {
        $payload = [
            'merchant_id' => config('zarinpal.merchant_id'),
            'currency' => config('zarinpal.currency', 'IRT'),
            'amount' => $amount,
            'authority' => $authority,
        ];

        $response = Http::asJson()->acceptJson()->post($this->verifyUrl(), $payload);

        $data = $response->json('data');

        // کد ۱۰۰: تراکنش موفق و برای اولین بار وریفای شده
        // کد ۱۰۱: تراکنش قبلاً وریفای شده (کاربر صفحه رو رفرش کرده)
        $successCodes = [100, 101];

        if (! $response->successful() || empty($data) || ! in_array($data['code'] ?? null, $successCodes, true)) {
            Log::warning('ZarinPal payment verify failed', [
                'sandbox' => config('zarinpal.sandbox', false),
                'payload' => $payload,
                'response' => $response->json(),
            ]);

            return [
                'success' => false,
                'message' => $data['message'] ?? 'تأیید پرداخت ناموفق بود.',
            ];
        }

        return [
            'success' => true,
            'ref_id' => (string) ($data['ref_id'] ?? ''),
            'already_verified' => ($data['code'] ?? null) === 101,
        ];
    }
}
