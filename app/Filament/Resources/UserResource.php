<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\Grade;
use App\Models\User;
use App\Support\JalaliDate;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static UnitEnum|string|null $navigationGroup = 'مدیریت کاربران';

    protected static ?string $navigationLabel = 'کاربران';

    protected static ?string $modelLabel = 'کاربر';

    protected static ?string $pluralModelLabel = 'کاربران';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('نام')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->label('ایمیل')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                Select::make('role')
                    ->label('نقش')
                    ->options([
                        'admin' => 'ادمین',
                        'student' => 'دانش‌آموز',
                    ])
                    ->required()
                    ->live(),

                Select::make('grade_id')
                    ->label('پایه')
                    ->options(fn () => Grade::query()->pluck('title', 'id'))
                    ->visible(fn (Get $get) => $get('role') === 'student')
                    ->required(fn (Get $get) => $get('role') === 'student'),

                TextInput::make('password')
                    ->label('رمز عبور')
                    ->password()
                    ->revealable()
                    ->required(fn (string $operation) => $operation === 'create')
                    ->dehydrated(fn ($state) => filled($state))
                    ->helperText(fn (string $operation) => $operation === 'edit'
                        ? 'برای تغییر رمز عبور پر کنید، در غیر این صورت خالی بگذارید.'
                        : null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('نام')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('ایمیل')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('roles.name')
                    ->label('نقش')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'admin' => 'ادمین',
                        'student' => 'دانش‌آموز',
                        default => $state,
                    })
                    ->color(fn (string $state) => $state === 'admin' ? 'danger' : 'success'),

                TextColumn::make('grade.title')
                    ->label('پایه')
                    ->placeholder('—'),

                TextColumn::make('created_at')
                    ->label('تاریخ ثبت‌نام')
                    ->formatStateUsing(fn ($state) => JalaliDate::format($state))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->label('نقش')
                    ->options([
                        'admin' => 'ادمین',
                        'student' => 'دانش‌آموز',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'] ?? null,
                            fn (Builder $q, $role) => $q->whereHas(
                                'roles',
                                fn (Builder $q) => $q->where('name', $role)
                            )
                        );
                    }),

                SelectFilter::make('grade_id')
                    ->label('پایه')
                    ->relationship('grade', 'title'),
            ])
            ->recordActions([
                EditAction::make(),

                Action::make('resetPassword')
                    ->label('ریست رمز عبور')
                    ->icon('heroicon-o-key')
                    ->color('warning')
                    ->schema([
                        TextInput::make('new_password')
                            ->label('رمز عبور جدید')
                            ->password()
                            ->revealable()
                            ->required()
                            ->minLength(8),
                    ])
                    ->action(function (array $data, User $record): void {
                        $record->update([
                            'password' => $data['new_password'],
                        ]);
                    }),

                DeleteAction::make()
                    ->visible(fn (User $record) => $record->id !== auth()->id()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
