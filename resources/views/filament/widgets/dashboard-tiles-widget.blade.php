@php
    use App\Support\JalaliDate;
@endphp

<div class="fi-dashboard-tiles flex w-full flex-col gap-6" style="grid-column: 1 / -1;">
    @foreach ($this->getTileGroups() as $group)
        <div>
            <h3 class="mb-2 text-sm font-semibold text-gray-500 dark:text-gray-400">
                {{ $group['label'] }}
            </h3>

            <div
                class="grid w-full gap-4"
                style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));"
            >
                @foreach ($group['tiles'] as $tile)
                    <a
                        href="{{ $tile['url'] }}"
                        wire:navigate
                        class="flex min-h-[150px] flex-col justify-between overflow-hidden rounded-2xl p-5 text-white shadow-sm transition-colors {{ $tile['card'] }}"
                    >
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <div class="truncate text-sm font-medium text-white/90">
                                    {{ $tile['label'] }}
                                </div>
                                <div class="mt-1 text-3xl font-extrabold leading-tight text-white">
                                    {{ JalaliDate::toPersianDigits($tile['count']) }}
                                </div>
                            </div>

                            <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/15 shadow-inner ring-1 ring-white/10 [&_svg]:h-7 [&_svg]:w-7 [&_svg]:text-white [&_svg]:drop-shadow-sm">
                                {{ \Filament\Support\generate_icon_html($tile['icon'], size: \Filament\Support\Enums\IconSize::ExtraLarge) }}
                            </span>
                        </div>

                        <div class="mt-4 flex items-center gap-1 border-t border-white/20 pt-3 text-xs font-medium text-white/85">
                            <span>اطلاعات بیشتر</span>
                            <span>←</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
