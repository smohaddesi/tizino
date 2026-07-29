<?php

namespace App\Filament\Resources\Grades;

use App\Filament\Resources\Grades\GradeResource\Pages;
use App\Models\Grade;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;

class GradeResource extends Resource
{
    protected static ?string $model = Grade::class;

    protected static string $navigationIcon = 'heroicon-o-academic-cap';
    
    protected static string $navigationGroup = 'مدیریت پایه‌ها';
    
    protected static ?int $navigationSort = 1;

    public static function getModelLabel(): string
    {
        return 'پایه تحصیلی';
    }

    public static function getPluralModelLabel(): string
    {
        return 'پایه‌های تحصیلی';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('نام پایه')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                    
                Forms\Components\TextInput::make('code')
                    ->label('کد پایه')
                    ->required()
                    ->maxLength(50)
                    ->unique(ignoreRecord: true)
                    ->helperText('مثال: GRADE-001'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('نام پایه')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('code')
                    ->label('کد پایه')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاریخ ایجاد')
                    ->dateTime('Y/m/d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('آخرین بروزرسانی')
                    ->dateTime('Y/m/d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('ویرایش'),
                Tables\Actions\DeleteAction::make()
                    ->label('حذف'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('حذف انتخابی'),
                ]),
            ]);
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
            'index' => Pages\ListGrades::route('/'),
            'create' => Pages\CreateGrade::route('/create'),
            'edit' => Pages\EditGrade::route('/{record}/edit'),
        ];
    }
}