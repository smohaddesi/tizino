<?php

namespace App\Filament\Resources\Exams;

use App\Filament\Resources\Exams\Pages\CreateExam;
use App\Filament\Resources\Exams\Pages\EditExam;
use App\Filament\Resources\Exams\Pages\ListExams;
use App\Filament\Resources\Exams\RelationManagers\QuestionsRelationManager;
use App\Filament\Resources\Exams\Schemas\ExamForm;
use App\Filament\Resources\Exams\Tables\ExamsTable;
use App\Models\Exam;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class ExamResource extends Resource
{
    protected static ?string $model = Exam::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static string|UnitEnum|null $navigationGroup = 'سیستم آزمون';

    protected static ?string $navigationLabel = 'آزمون‌ها';

    protected static ?string $modelLabel = 'آزمون';

    protected static ?string $pluralModelLabel = 'آزمون‌ها';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return ExamForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExamsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            QuestionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExams::route('/'),
            'create' => CreateExam::route('/create'),
            'edit' => EditExam::route('/{record}/edit'),
        ];
    }
}
