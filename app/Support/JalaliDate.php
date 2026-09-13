<?php

namespace App\Support;

use Carbon\CarbonInterface;
use DateTimeInterface;

/**
 * تبدیل تاریخ میلادی به شمسی، فقط برای نمایش.
 *
 * تاریخ‌ها همچنان به‌صورت میلادی در دیتابیس ذخیره می‌شوند؛ این کلاس صرفاً
 * رشته‌ی نمایشی (شمسی) رو تولید می‌کنه و هیچ تغییری در مقدار ذخیره‌شده نمی‌ده.
 */
class JalaliDate
{
    /**
     * نام کامل ماه‌های شمسی.
     */
    private const MONTHS = [
        1 => 'فروردین', 2 => 'اردیبهشت', 3 => 'خرداد',
        4 => 'تیر', 5 => 'مرداد', 6 => 'شهریور',
        7 => 'مهر', 8 => 'آبان', 9 => 'آذر',
        10 => 'دی', 11 => 'بهمن', 12 => 'اسفند',
    ];

    /**
     * نام کامل روزهای هفته (0 = یکشنبه ... مطابق w در PHP که 0=یکشنبه نیست،
     * پس با dayOfWeek کربن که 0=یکشنبه هست هماهنگ شده).
     */
    private const WEEKDAYS = [
        0 => 'یکشنبه', 1 => 'دوشنبه', 2 => 'سه‌شنبه', 3 => 'چهارشنبه',
        4 => 'پنجشنبه', 5 => 'جمعه', 6 => 'شنبه',
    ];

    private const DIGITS_FA = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];

    /**
     * ورودی رو (Carbon/DateTime/string/null) به رشته‌ی تاریخ شمسی تبدیل می‌کنه.
     *
     * فرمت‌های پشتیبانی‌شده (شبیه فرمت date() خود پی‌اچ‌پی):
     * Y: سال ۴ رقمی، y: سال ۲ رقمی، m: ماه ۲ رقمی، n: ماه بدون صفر،
     * F: نام کامل ماه، d: روز ۲ رقمی، j: روز بدون صفر، l: نام کامل روز هفته،
     * H: ساعت ۲۴ ساعته ۲ رقمی، i: دقیقه ۲ رقمی، s: ثانیه ۲ رقمی.
     * سایر کاراکترها (مثل / یا -) عیناً در خروجی باقی می‌مونن.
     *
     * @param  DateTimeInterface|CarbonInterface|string|null  $date
     */
    public static function format($date, string $format = 'Y/m/d H:i', bool $persianDigits = true): ?string
    {
        if ($date === null || $date === '') {
            return null;
        }

        if (is_string($date)) {
            try {
                $date = \Carbon\Carbon::parse($date);
            } catch (\Throwable $e) {
                return null;
            }
        }

        if (! $date instanceof DateTimeInterface) {
            return null;
        }

        [$jy, $jm, $jd] = self::toJalali((int) $date->format('Y'), (int) $date->format('n'), (int) $date->format('j'));

        $weekday = self::WEEKDAYS[(int) $date->format('w')] ?? '';

        $replacements = [
            'Y' => (string) $jy,
            'y' => substr((string) $jy, -2),
            'm' => str_pad((string) $jm, 2, '0', STR_PAD_LEFT),
            'n' => (string) $jm,
            'F' => self::MONTHS[$jm] ?? '',
            'd' => str_pad((string) $jd, 2, '0', STR_PAD_LEFT),
            'j' => (string) $jd,
            'l' => $weekday,
            'H' => $date->format('H'),
            'i' => $date->format('i'),
            's' => $date->format('s'),
        ];

        $output = strtr($format, $replacements);

        return $persianDigits ? self::toPersianDigits($output) : $output;
    }

    /**
     * تبدیل سال/ماه/روز میلادی به سال/ماه/روز شمسی.
     *
     * @return array{0:int,1:int,2:int} [jy, jm, jd]
     */
    public static function toJalali(int $gy, int $gm, int $gd): array
    {
        $gDaysInMonthCumulative = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];

        $gy2 = ($gm > 2) ? ($gy + 1) : $gy;

        $days = 355666
            + (365 * $gy)
            + intdiv($gy2 + 3, 4)
            - intdiv($gy2 + 99, 100)
            + intdiv($gy2 + 399, 400)
            + $gd
            + $gDaysInMonthCumulative[$gm - 1];

        $jy = -1595 + (33 * intdiv($days, 12053));
        $days %= 12053;

        $jy += 4 * intdiv($days, 1461);
        $days %= 1461;

        if ($days > 365) {
            $jy += intdiv($days - 1, 365);
            $days = ($days - 1) % 365;
        }

        if ($days < 186) {
            $jm = 1 + intdiv($days, 31);
            $jd = 1 + ($days % 31);
        } else {
            $jm = 7 + intdiv($days - 186, 30);
            $jd = 1 + (($days - 186) % 30);
        }

        return [$jy, $jm, $jd];
    }

    /**
     * تبدیل ارقام انگلیسی داخل یک رشته به ارقام فارسی.
     */
    public static function toPersianDigits(string $value): string
    {
        return strtr($value, [
            '0' => self::DIGITS_FA[0], '1' => self::DIGITS_FA[1], '2' => self::DIGITS_FA[2],
            '3' => self::DIGITS_FA[3], '4' => self::DIGITS_FA[4], '5' => self::DIGITS_FA[5],
            '6' => self::DIGITS_FA[6], '7' => self::DIGITS_FA[7], '8' => self::DIGITS_FA[8],
            '9' => self::DIGITS_FA[9],
        ]);
    }

    /**
     * نام کامل ماه‌های شمسی، به ترتیب شماره‌ی ماه (۱ تا ۱۲).
     *
     * @return array<int, string>
     */
    public static function monthNames(): array
    {
        return self::MONTHS;
    }
}
