<?php

namespace App\Filament\Resources\QuestionOptions;

use App\Filament\Resources\QuestionOptions\Pages\CreateQuestionOption;
use App\Filament\Resources\QuestionOptions\Pages\EditQuestionOption;
use App\Filament\Resources\QuestionOptions\Pages\ListQuestionOptions;
use App\Filament\Resources\QuestionOptions\Schemas\QuestionOptionForm;
use App\Filament\Resources\QuestionOptions\Tables\QuestionOptionsTable;
use App\Models\QuestionOption;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class QuestionOptionResource extends Resource
{
    protected static ?string $model = QuestionOption::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'بانک سوال';

    protected static ?string $navigationLabel = 'گزینه‌های سؤال';

    protected static ?string $modelLabel = 'گزینه‌ی سؤال';

    protected static ?string $pluralModelLabel = 'گزینه‌های سؤال';

    protected static ?int $navigationSort = 5;

    protected static ?string $recordTitleAttribute = 'body';

    /**
     * گزینه‌های سؤال دیگه از داخل فرم خود سؤال (Repeater) مدیریت می‌شن،
     * پس این Resource رو از منوی کناری مخفی می‌کنیم تا کار تکراری نباشه.
     * خود Resource برای دسترسی مستقیم احتمالی (مثلاً از طریق آدرس مستقیم) باقی می‌مونه.
     */
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return QuestionOptionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return QuestionOptionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListQuestionOptions::route('/'),
            'create' => CreateQuestionOption::route('/create'),
            'edit' => EditQuestionOption::route('/{record}/edit'),
        ];
    }
}
