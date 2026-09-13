<?php

namespace App\Filament\Forms\Components;

use App\Support\JalaliDate;
use Closure;
use Filament\Forms\Components\Field;
use Illuminate\Support\Carbon;
use Throwable;

/**
 * انتخاب‌گر تاریخ و ساعت شمسی، بدون وابستگی به هیچ پکیج خارجی.
 *
 * مقدار ذخیره‌شده در دیتابیس همچنان میلادی است (دقیقاً همون رشته‌ای که
 * DateTimePicker خود Filament هم تولید می‌کرد: 'Y-m-d H:i:s')؛ فقط رابط
 * کاربری انتخاب تاریخ شمسیه. تبدیل شمسی↔میلادی سمت کلاینت (جاوااسکریپت)
 * انجام می‌شه تا نیازی به رفت‌وبرگشت با سرور نباشه.
 */
class JalaliDateTimePicker extends Field
{
    protected string $view = 'filament.forms.components.jalali-date-time-picker';

    protected bool | Closure $hasSeconds = false;

    protected bool | Closure $hasTime = true;

    protected int | Closure $yearsBack = 1;

    protected int | Closure $yearsForward = 3;

    protected function setUp(): void
    {
        parent::setUp();

        // همیشه قبل از ذخیره، به یک رشته‌ی میلادی نرمال تبدیل می‌شه.
        $this->dehydrateStateUsing(static function ($state) {
            if (blank($state)) {
                return null;
            }

            try {
                return Carbon::parse($state)->format('Y-m-d H:i:s');
            } catch (Throwable $e) {
                return null;
            }
        });
    }

    public function seconds(bool | Closure $condition = true): static
    {
        $this->hasSeconds = $condition;

        return $this;
    }

    public function hasSeconds(): bool
    {
        return (bool) $this->evaluate($this->hasSeconds);
    }

    public function withoutTime(): static
    {
        $this->hasTime = false;

        return $this;
    }

    public function hasTime(): bool
    {
        return (bool) $this->evaluate($this->hasTime);
    }

    /**
     * چند سال شمسی قبل از سال جاری توی لیست انتخاب سال نشون داده بشه.
     */
    public function yearsBack(int | Closure $years): static
    {
        $this->yearsBack = $years;

        return $this;
    }

    /**
     * چند سال شمسی بعد از سال جاری توی لیست انتخاب سال نشون داده بشه.
     */
    public function yearsForward(int | Closure $years): static
    {
        $this->yearsForward = $years;

        return $this;
    }

    /**
     * سال شمسی جاری، برای تعیین بازه‌ی پیش‌فرض لیست سال‌ها.
     */
    public function getCurrentJalaliYear(): int
    {
        $now = Carbon::now();

        [$jy] = JalaliDate::toJalali((int) $now->format('Y'), (int) $now->format('n'), (int) $now->format('j'));

        return $jy;
    }

    /**
     * @return array<int>
     */
    public function getSelectableYears(): array
    {
        $current = $this->getCurrentJalaliYear();
        $back = (int) $this->evaluate($this->yearsBack);
        $forward = (int) $this->evaluate($this->yearsForward);

        return range($current - $back, $current + $forward);
    }

    /**
     * مقدار اولیه (میلادی، از دیتابیس) رو به بخش‌های شمسی برای پیش‌پرکردن فرم تبدیل می‌کنه.
     *
     * @return array{y: int|null, m: int|null, d: int|null, time: string|null}
     */
    public function getInitialJalaliParts(): array
    {
        $state = $this->getState();

        if (blank($state)) {
            return ['y' => null, 'm' => null, 'd' => null, 'time' => null];
        }

        try {
            $date = Carbon::parse($state);
        } catch (Throwable $e) {
            return ['y' => null, 'm' => null, 'd' => null, 'time' => null];
        }

        [$jy, $jm, $jd] = JalaliDate::toJalali((int) $date->format('Y'), (int) $date->format('n'), (int) $date->format('j'));

        return [
            'y' => $jy,
            'm' => $jm,
            'd' => $jd,
            'time' => $date->format($this->hasSeconds() ? 'H:i:s' : 'H:i'),
        ];
    }
}
