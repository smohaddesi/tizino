<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Services\ZarinPalService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function __construct(private readonly ZarinPalService $zarinPal)
    {
    }

    /**
     * نمایش لیست پلن‌های اشتراک و وضعیت فعلی اشتراک کاربر.
     */
    public function plans(): View
    {
        $plans = SubscriptionPlan::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $activeSubscription = Auth::user()->activeSubscription();

        return view('subscriptions.plans', [
            'plans' => $plans,
            'activeSubscription' => $activeSubscription,
        ]);
    }

    /**
     * شروع فرآیند پرداخت: ثبت رکورد Payment و ریدایرکت به درگاه زرین‌پال.
     */
    public function checkout(SubscriptionPlan $plan): RedirectResponse
    {
        abort_unless($plan->is_active, 404);

        $user = Auth::user();

        $payment = Payment::create([
            'user_id' => $user->id,
            'subscription_plan_id' => $plan->id,
            'amount' => $plan->price,
            'gateway' => 'zarinpal',
            'status' => 'pending',
        ]);

        $result = $this->zarinPal->request(
            amount: $plan->price,
            description: 'خرید '.$plan->title.' - تیزینو',
            callbackUrl: route('payment.callback'),
            email: $user->email,
        );

        if (! $result['success']) {
            $payment->update(['status' => 'failed']);

            return redirect()
                ->route('subscriptions.plans')
                ->with('error', $result['message']);
        }

        $payment->update(['authority' => $result['authority']]);

        return redirect()->away($this->zarinPal->startPayUrl($result['authority']));
    }

    /**
     * بازگشت کاربر از درگاه زرین‌پال؛ تأیید پرداخت و فعال‌سازی اشتراک.
     */
    public function callback(Request $request): RedirectResponse
    {
        $authority = (string) $request->query('Authority');
        $status = $request->query('Status');

        $payment = Payment::query()->where('authority', $authority)->first();

        if (! $payment) {
            return redirect()->route('subscriptions.plans')->with('error', 'تراکنش یافت نشد.');
        }

        if ($payment->status === 'success') {
            return redirect()->route('subscriptions.plans')->with('success', 'این پرداخت قبلاً با موفقیت ثبت شده است.');
        }

        if ($status !== 'OK') {
            $payment->update(['status' => 'failed']);

            return redirect()->route('subscriptions.plans')->with('error', 'پرداخت توسط شما لغو شد.');
        }

        $result = $this->zarinPal->verify($payment->amount, $authority);

        if (! $result['success']) {
            $payment->update(['status' => 'failed']);

            return redirect()->route('subscriptions.plans')->with('error', $result['message']);
        }

        DB::transaction(function () use ($payment, $result): void {
            $plan = $payment->plan;
            $user = $payment->user;

            $current = $user->activeSubscription();
            $startsAt = $current ? $current->ends_at->copy() : now();
            $endsAt = $startsAt->copy()->addDays($plan->duration_days);

            $subscription = Subscription::create([
                'user_id' => $user->id,
                'subscription_plan_id' => $plan->id,
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
                'status' => 'active',
            ]);

            $payment->update([
                'status' => 'success',
                'ref_id' => $result['ref_id'],
                'paid_at' => now(),
                'subscription_id' => $subscription->id,
            ]);
        });

        return redirect()->route('subscriptions.plans')->with('success', 'اشتراک شما با موفقیت فعال شد.');
    }
}
