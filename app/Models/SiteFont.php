<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SiteFont extends Model
{
    protected $fillable = ['family', 'variants', 'is_active'];

    protected $casts = ['variants' => 'array', 'is_active' => 'boolean'];

    public const FORMATS = [
        'woff2' => 'woff2',
        'woff' => 'woff',
        'ttf' => 'truetype',
        'otf' => 'opentype',
    ];

    public static function formatFromPath(string $path): string
    {
        return self::FORMATS[strtolower(pathinfo($path, PATHINFO_EXTENSION))] ?? 'truetype';
    }

    /** فعال‌سازی این فونت و غیرفعال شدن بقیه. */
    public function activate(): void
    {
        static::query()->where('id', '!=', $this->id)->update(['is_active' => false]);
        $this->update(['is_active' => true]);
    }

    /** CSS فونت فعال؛ رشته‌ی خالی اگر فونتی فعال نباشد. */
    public static function activeCss(): string
    {
        try {
            $font = static::query()->where('is_active', true)->first();
        } catch (\Throwable $e) {
            return '';
        }

        if (! $font || empty($font->variants)) {
            return '';
        }

        $family = str_replace(["'", '"', '\\', '<', '>', ';', '{', '}'], '', $font->family);
        $css = '';

        foreach ($font->variants as $v) {
            // مسیر موقت Livewire (آپلودِ نهایی‌نشده) فایل واقعی نیست
            if (empty($v['file_path']) || str_starts_with($v['file_path'], 'livewire-file:')) {
                continue;
            }

            $url = Storage::disk('site_fonts')->url($v['file_path']);
            $weight = (int) ($v['weight'] ?? 400);
            $format = self::formatFromPath($v['file_path']);

            $css .= "@font-face{font-family:'{$family}';font-weight:{$weight};font-style:normal;font-display:swap;"
                ."src:url('{$url}') format('{$format}');}\n";
        }

        if ($css === '') {
            return '';
        }

        $css .= "body,button,input,select,textarea,summary{font-family:'{$family}','Yekan','Vazirmatn',Tahoma,sans-serif !important;}\n";

        return $css;
    }
}
