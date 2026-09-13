@php
    $statePath = $getStatePath();
    $initial = $getInitialJalaliParts();
    $months = \App\Support\JalaliDate::monthNames();
    $withTime = $hasTime();
    $withSeconds = $hasSeconds();
    $isFieldDisabled = $isDisabled();

    $entangleExpression = $applyStateBindingModifiers("\$entangle('{$statePath}')");
    $initialTimeJs = $initial['time'] ? "'{$initial['time']}'" : "'00:00'";
    $initialYearJs = $initial['y'] !== null ? "'{$initial['y']}'" : "''";
    $initialMonthJs = $initial['m'] !== null ? "'{$initial['m']}'" : "''";
    $initialDayJs = $initial['d'] !== null ? "'{$initial['d']}'" : "''";
    $monthNamesJs = '[' . collect($months)->map(fn ($name) => "'{$name}'")->implode(',') . ']';
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div
        x-data="{
            open: false,
            state: $wire.{{ $entangleExpression }},
            year: {{ $initialYearJs }},
            month: {{ $initialMonthJs }},
            day: {{ $initialDayJs }},
            time: {{ $initialTimeJs }},
            withTime: {{ $withTime ? 'true' : 'false' }},
            monthNames: {{ $monthNamesJs }},
            viewYear: null,
            viewMonth: null,

            toFa(value) {
                const map = { '0': '۰', '1': '۱', '2': '۲', '3': '۳', '4': '۴', '5': '۵', '6': '۶', '7': '۷', '8': '۸', '9': '۹' };
                return String(value).replace(/[0-9]/g, (d) => map[d]);
            },

            jalaliToGregorian(jy, jm, jd) {
                jy = parseInt(jy, 10) + 1595;
                jm = parseInt(jm, 10);
                jd = parseInt(jd, 10);

                let days = -355668 + (365 * jy) + (Math.floor(jy / 33) * 8) + Math.floor(((jy % 33) + 3) / 4) + jd;
                days += (jm < 7) ? (jm - 1) * 31 : ((jm - 7) * 30) + 186;

                let gy = 400 * Math.floor(days / 146097);
                days %= 146097;

                if (days > 36524) {
                    days -= 1;
                    gy += 100 * Math.floor(days / 36524);
                    days %= 36524;
                    if (days >= 365) days += 1;
                }

                gy += 4 * Math.floor(days / 1461);
                days %= 1461;

                if (days > 365) {
                    gy += Math.floor((days - 1) / 365);
                    days = (days - 1) % 365;
                }

                let gd = days + 1;
                const isLeap = ((gy % 4 === 0) && (gy % 100 !== 0)) || (gy % 400 === 0);
                const monthDays = [0, 31, isLeap ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
                let gm = 0;
                for (let i = 1; i <= 12; i++) {
                    if (gd <= monthDays[i]) { gm = i; break; }
                    gd -= monthDays[i];
                }

                return [gy, gm, gd];
            },

            gregorianToJalali(gy, gm, gd) {
                const gDaysCumulative = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
                const gy2 = (gm > 2) ? (gy + 1) : gy;

                let days = 355666 + (365 * gy) + Math.floor((gy2 + 3) / 4) - Math.floor((gy2 + 99) / 100) + Math.floor((gy2 + 399) / 400) + gd + gDaysCumulative[gm - 1];

                let jy = -1595 + (33 * Math.floor(days / 12053));
                days %= 12053;

                jy += 4 * Math.floor(days / 1461);
                days %= 1461;

                if (days > 365) {
                    jy += Math.floor((days - 1) / 365);
                    days = (days - 1) % 365;
                }

                let jm, jd;
                if (days < 186) {
                    jm = 1 + Math.floor(days / 31);
                    jd = 1 + (days % 31);
                } else {
                    jm = 7 + Math.floor((days - 186) / 30);
                    jd = 1 + ((days - 186) % 30);
                }

                return [jy, jm, jd];
            },

            daysInJalaliMonth(jy, jm) {
                if (jy === '' || jm === '' || jy === null || jm === null) return 31;

                jy = parseInt(jy, 10);
                jm = parseInt(jm, 10);

                let nextY = jy, nextM = jm + 1;
                if (nextM > 12) { nextM = 1; nextY = jy + 1; }

                const [gy1, gm1, gd1] = this.jalaliToGregorian(jy, jm, 1);
                const [gy2, gm2, gd2] = this.jalaliToGregorian(nextY, nextM, 1);

                const d1 = Date.UTC(gy1, gm1 - 1, gd1);
                const d2 = Date.UTC(gy2, gm2 - 1, gd2);

                return Math.round((d2 - d1) / 86400000);
            },

            pad(n) {
                return String(n).padStart(2, '0');
            },

            todayJalali() {
                const now = new Date();
                return this.gregorianToJalali(now.getFullYear(), now.getMonth() + 1, now.getDate());
            },

            initView() {
                if (this.year !== '' && this.month !== '') {
                    this.viewYear = parseInt(this.year, 10);
                    this.viewMonth = parseInt(this.month, 10);
                } else {
                    const [ty, tm] = this.todayJalali();
                    this.viewYear = ty;
                    this.viewMonth = tm;
                }
            },

            prevMonth() {
                this.viewMonth -= 1;
                if (this.viewMonth < 1) { this.viewMonth = 12; this.viewYear -= 1; }
            },

            nextMonth() {
                this.viewMonth += 1;
                if (this.viewMonth > 12) { this.viewMonth = 1; this.viewYear += 1; }
            },

            get calendarCells() {
                const [gy, gm, gd] = this.jalaliToGregorian(this.viewYear, this.viewMonth, 1);
                const jsWeekday = new Date(Date.UTC(gy, gm - 1, gd)).getUTCDay();
                const leading = (jsWeekday + 1) % 7;
                const totalDays = this.daysInJalaliMonth(this.viewYear, this.viewMonth);

                const cells = [];
                for (let i = 0; i < leading; i++) {
                    cells.push({ day: null, key: 'b' + i });
                }
                for (let d = 1; d <= totalDays; d++) {
                    cells.push({ day: d, key: 'd' + d });
                }

                return cells;
            },

            isSelected(day) {
                return this.year !== ''
                    && this.month !== ''
                    && this.day !== ''
                    && parseInt(this.year, 10) === this.viewYear
                    && parseInt(this.month, 10) === this.viewMonth
                    && parseInt(this.day, 10) === day;
            },

            selectDay(day) {
                if (day === null) return;

                this.year = String(this.viewYear);
                this.month = String(this.viewMonth);
                this.day = String(day);
                this.sync();
            },

            goToday() {
                const [ty, tm, td] = this.todayJalali();
                this.year = String(ty);
                this.month = String(tm);
                this.day = String(td);
                this.viewYear = ty;
                this.viewMonth = tm;

                if (this.withTime) {
                    const now = new Date();
                    this.time = this.pad(now.getHours()) + ':' + this.pad(now.getMinutes());
                }

                this.sync();
            },

            clearDate() {
                this.year = '';
                this.month = '';
                this.day = '';
                this.sync();
            },

            sync() {
                if (this.day !== '' && parseInt(this.day, 10) > this.daysInJalaliMonth(this.year, this.month)) {
                    this.day = String(this.daysInJalaliMonth(this.year, this.month));
                }

                if (this.year === '' || this.month === '' || this.day === '') {
                    this.state = null;
                    return;
                }

                const [gy, gm, gd] = this.jalaliToGregorian(this.year, this.month, this.day);

                let timePart = '00:00:00';
                if (this.withTime && this.time) {
                    const parts = this.time.split(':');
                    timePart = this.pad(parts[0] || 0) + ':' + this.pad(parts[1] || 0) + ':' + this.pad(parts[2] || 0);
                }

                this.state = gy + '-' + this.pad(gm) + '-' + this.pad(gd) + ' ' + timePart;
            },

            get displayText() {
                if (this.year === '' || this.month === '' || this.day === '') {
                    return null;
                }

                let text = this.toFa(this.day) + ' ' + this.monthNames[parseInt(this.month, 10) - 1] + ' ' + this.toFa(this.year);

                if (this.withTime && this.time) {
                    text += ' - ' + this.toFa(this.time);
                }

                return text;
            },
        }"
        x-init="sync(); initView();"
        class="relative"
    >
        <button
            type="button"
            @click="open = !open; if (open) initView();"
            @disabled($isFieldDisabled)
            class="fi-input flex w-full items-center justify-between gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-950 shadow-sm transition duration-75 focus:border-primary-500 focus:outline-none focus:ring-1 focus:ring-primary-500 disabled:opacity-70 dark:border-gray-600 dark:bg-white/5 dark:text-white"
        >
            <span x-text="displayText ?? 'انتخاب تاریخ و ساعت'" :class="{ 'text-gray-400 dark:text-gray-500': !displayText }"></span>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 shrink-0 text-gray-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-2.25Zm-2.25-4.5h.008v.008H9.75v-.008Zm0 2.25h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-2.25Zm-2.25-4.5h.008v.008H7.5v-.008Zm0 2.25h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-2.25Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-2.25Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
            </svg>
        </button>

        <div
            x-show="open"
            x-on:click.outside="open = false"
            x-transition
            style="display: none;"
            class="absolute z-50 mt-2 w-72 rounded-xl border border-gray-200 bg-white p-3 shadow-lg dark:border-gray-700 dark:bg-gray-800"
        >
            <div class="mb-2 flex items-center justify-between">
                <button type="button" @click="prevMonth()" class="rounded p-1 text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>

                <span class="text-sm font-medium text-gray-700 dark:text-gray-200" x-text="monthNames[viewMonth - 1] + ' ' + toFa(viewYear)"></span>

                <button type="button" @click="nextMonth()" class="rounded p-1 text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5l-7.5-7.5 7.5-7.5" />
                    </svg>
                </button>
            </div>

            <div class="mb-1 grid grid-cols-7 gap-1 text-center text-[11px] text-gray-400">
                <div>ش</div><div>ی</div><div>د</div><div>س</div><div>چ</div><div>پ</div><div>ج</div>
            </div>

            <div class="grid grid-cols-7 gap-1">
                <template x-for="cell in calendarCells" :key="cell.key">
                    <button
                        type="button"
                        x-show="cell.day !== null"
                        x-text="cell.day !== null ? toFa(cell.day) : ''"
                        @click="selectDay(cell.day)"
                        :class="isSelected(cell.day) ? 'bg-primary-600 text-white' : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'"
                        class="flex h-8 w-8 items-center justify-center rounded-full text-xs"
                    ></button>
                </template>
            </div>

            @if ($withTime)
                <div class="mt-3 border-t border-gray-100 pt-3 dark:border-gray-700">
                    <input
                        type="time"
                        @if ($withSeconds) step="1" @else step="60" @endif
                        x-model="time"
                        x-on:change="sync()"
                        class="w-full rounded-lg border-gray-300 py-1.5 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    />
                </div>
            @endif

            <div class="mt-3 flex items-center justify-between text-xs">
                <button type="button" @click="clearDate()" class="text-gray-400 hover:text-danger-500">پاک کردن</button>
                <button type="button" @click="goToday()" class="text-primary-600 hover:underline">امروز</button>
                <button type="button" @click="open = false" class="font-medium text-primary-600 hover:underline">تأیید</button>
            </div>
        </div>
    </div>
</x-dynamic-component>
