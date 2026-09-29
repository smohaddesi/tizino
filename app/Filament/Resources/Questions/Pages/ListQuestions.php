<?php

namespace App\Filament\Resources\Questions\Pages;

use App\Filament\Resources\Questions\QuestionResource;
use App\Models\Question;
use App\Models\Topic;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;

class ListQuestions extends ListRecords
{
    protected static string $resource = QuestionResource::class;

    private const TRUTHY = ['1', 'true', 'yes', 'بله', 'صحیح'];

    private const META_COLUMNS = 8; // درس، موضوع، متن سؤال، سختی، زمان پاسخ، منبع، توضیح پاسخ، فعال

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadTemplate')
                ->label('دانلود قالب CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(function () {
                    $bom = "\xEF\xBB\xBF";

                    $header = [
                        'درس', 'موضوع', 'متن سؤال', 'سطح سختی (۱ تا ۵)', 'زمان پاسخ (ثانیه — اختیاری)',
                        'منبع', 'توضیح پاسخ', 'فعال (۱ یا ۰)',
                        'گزینه ۱', 'گزینه ۱ صحیح (۱ یا ۰)',
                        'گزینه ۲', 'گزینه ۲ صحیح (۱ یا ۰)',
                        'گزینه ۳', 'گزینه ۳ صحیح (۱ یا ۰)',
                        'گزینه ۴', 'گزینه ۴ صحیح (۱ یا ۰)',
                    ];

                    $example = [
                        'هوش کلامی', 'تشبیه و قیاس', 'کتاب : خواندن :: قلم : ؟', '2', '75',
                        '', 'رابطه‌ی ابزار و کاربردش', '1',
                        'پاک‌کن', '0',
                        'نوشتن', '1',
                        'کاغذ', '0',
                        'رنگ', '0',
                    ];

                    $csv = $bom;
                    foreach ([$header, $example] as $row) {
                        $csv .= implode(',', array_map(
                            fn ($cell) => '"'.str_replace('"', '""', (string) $cell).'"',
                            $row
                        ))."\r\n";
                    }

                    return response()->streamDownload(
                        fn () => print($csv),
                        'questions-template.csv',
                        ['Content-Type' => 'text/csv; charset=UTF-8']
                    );
                }),

            Action::make('reviewImport')
                ->label('بررسی فایل CSV')
                ->icon('heroicon-o-document-magnifying-glass')
                ->color('gray')
                ->schema([
                    FileUpload::make('file')
                        ->label('فایل CSV')
                        ->required()
                        ->acceptedFileTypes(['text/csv', 'text/plain', 'application/vnd.ms-excel'])
                        ->disk('local')
                        ->directory('imports')
                        ->visibility('private')
                        ->helperText('می‌تونی بیشتر از ۴ گزینه هم بذاری — فقط کافیه دو ستون «گزینه» و «صحیح بودنش» رو برای هر گزینه‌ی اضافه، به انتهای ردیف اضافه کنی.'),
                ])
                ->action(function (array $data) {
                    $fullPath = Storage::disk('local')->path($data['file']);
                    $result = self::processCsv($fullPath, commit: false);

                    session(['question_import_file' => $data['file']]);

                    $lines = [];
                    $lines[] = "✅ {$result['validCount']} ردیف آماده‌ی ثبته.";

                    if (! empty($result['errors'])) {
                        $lines[] = '⚠️ '.count($result['errors']).' ردیف مشکل داره:';
                        foreach (array_slice($result['errors'], 0, 15) as $error) {
                            $lines[] = '- '.$error;
                        }
                    }

                    Notification::make()
                        ->title('نتیجه‌ی بررسی فایل')
                        ->body(implode("\n", $lines))
                        ->color($result['validCount'] > 0 ? 'success' : 'danger')
                        ->persistent()
                        ->send();

                    if ($result['validCount'] > 0) {
                        Notification::make()
                            ->title('برای ثبت نهایی، دکمه‌ی «ثبت نهایی از آخرین بررسی» رو بزن.')
                            ->info()
                            ->send();
                    }
                }),

            Action::make('commitImport')
                ->label('ثبت نهایی از آخرین بررسی')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('ثبت نهایی سؤال‌های این فایل؟')
                ->modalDescription('سؤال‌های معتبر از آخرین فایلی که «بررسی» کردی، توی بانک سؤال ثبت می‌شن.')
                ->visible(function () {
                    $path = session('question_import_file');

                    return filled($path) && Storage::disk('local')->exists($path);
                })
                ->action(function () {
                    $path = session('question_import_file');

                    if (! $path || ! Storage::disk('local')->exists($path)) {
                        Notification::make()
                            ->title('فایلی برای ثبت پیدا نشد؛ اول یه فایل رو بررسی کن.')
                            ->danger()
                            ->send();

                        return;
                    }

                    $fullPath = Storage::disk('local')->path($path);
                    $result = self::processCsv($fullPath, commit: true);

                    Storage::disk('local')->delete($path);
                    session()->forget('question_import_file');

                    if ($result['createdCount'] > 0) {
                        Notification::make()
                            ->title("{$result['createdCount']} سؤال با موفقیت ثبت شد")
                            ->success()
                            ->send();
                    }

                    if (! empty($result['errors'])) {
                        Notification::make()
                            ->title(count($result['errors']).' ردیف رد شد')
                            ->body(implode("\n", array_slice($result['errors'], 0, 15)))
                            ->danger()
                            ->persistent()
                            ->send();
                    }
                }),

            CreateAction::make(),
        ];
    }

    /**
     * @return array{validCount: int, createdCount: int, errors: array<int, string>}
     */
    private static function processCsv(string $path, bool $commit): array
    {
        $handle = fopen($path, 'r');

        if ($handle === false) {
            return ['validCount' => 0, 'createdCount' => 0, 'errors' => ['فایل قابل خواندن نبود.']];
        }

        // رد کردن BOM احتمالی (سازگاری با اکسل فارسی)
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        fgetcsv($handle); // رد کردن ردیف هدر

        $errors = [];
        $validCount = 0;
        $createdCount = 0;
        $rowNumber = 1;

        while (($row = fgetcsv($handle)) !== false) {
            $rowNumber++;

            if (count(array_filter($row, fn ($cell) => trim((string) $cell) !== '')) === 0) {
                continue;
            }

            $meta = array_slice($row, 0, self::META_COLUMNS);
            $optionCells = array_slice($row, self::META_COLUMNS);

            [$subjectTitle, $topicTitle, $body, $difficulty, $answerTime, $source, $explanation, $isActive]
                = array_pad($meta, self::META_COLUMNS, null);

            $topic = Topic::query()
                ->whereHas('subject', fn ($q) => $q->where('title', trim((string) $subjectTitle)))
                ->where('title', trim((string) $topicTitle))
                ->first();

            if (! $topic) {
                $errors[] = "ردیف {$rowNumber}: موضوع «{$topicTitle}» زیر درس «{$subjectTitle}» پیدا نشد.";

                continue;
            }

            if (trim((string) $body) === '') {
                $errors[] = "ردیف {$rowNumber}: متن سؤال خالیه.";

                continue;
            }

            $validOptions = [];
            for ($i = 0; $i < count($optionCells); $i += 2) {
                $optBody = trim((string) ($optionCells[$i] ?? ''));

                if ($optBody === '') {
                    continue;
                }

                $validOptions[] = [$optBody, $optionCells[$i + 1] ?? null];
            }

            if (count($validOptions) < 2) {
                $errors[] = "ردیف {$rowNumber}: حداقل باید ۲ گزینه داشته باشه.";

                continue;
            }

            $correctCount = collect($validOptions)->filter(
                fn ($pair) => in_array(strtolower(trim((string) $pair[1])), self::TRUTHY, true)
            )->count();

            if ($correctCount === 0) {
                $errors[] = "ردیف {$rowNumber}: هیچ گزینه‌ی صحیحی مشخص نشده.";

                continue;
            }

            if ($correctCount > 1) {
                $errors[] = "ردیف {$rowNumber}: بیشتر از یک گزینه‌ی صحیح مشخص شده.";

                continue;
            }

            $validCount++;

            if ($commit) {
                $question = Question::create([
                    'topic_id' => $topic->id,
                    'body' => trim((string) $body),
                    'difficulty' => (int) ($difficulty !== null && $difficulty !== '' ? $difficulty : 2),
                    'answer_time' => $answerTime !== null && trim((string) $answerTime) !== '' ? (int) $answerTime : null,
                    'source' => trim((string) $source) !== '' ? trim((string) $source) : null,
                    'answer_explanation' => trim((string) $explanation) !== '' ? trim((string) $explanation) : null,
                    'is_active' => $isActive === null || $isActive === '' || in_array(strtolower(trim((string) $isActive)), self::TRUTHY, true),
                ]);

                $sortOrder = 0;
                foreach ($validOptions as [$optBody, $optCorrect]) {
                    $question->options()->create([
                        'body' => $optBody,
                        'is_correct' => in_array(strtolower(trim((string) $optCorrect)), self::TRUTHY, true),
                        'sort_order' => $sortOrder++,
                    ]);
                }

                $createdCount++;
            }
        }

        fclose($handle);

        return [
            'validCount' => $validCount,
            'createdCount' => $createdCount,
            'errors' => $errors,
        ];
    }
}
