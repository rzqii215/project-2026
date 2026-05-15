<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ActivityLogResource\Pages\ListActivityLogs;
use App\Filament\Admin\Resources\ActivityLogResource\Pages\ViewActivityLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Models\Activity;

class ActivityLogResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $slug = 'activity-logs';

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationLabel = 'Activity Log';

    protected static ?string $navigationGroup = 'Administration';

    protected static ?int $navigationSort = 3;

    public static function getModelLabel(): string
    {
        return 'Activity Log';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Activity Log';
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public static function canViewAny(): bool
    {
        return true;
    }

    public static function canView($record): bool
    {
        return true;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detail Activity Log')
                    ->schema([
                        Forms\Components\TextInput::make('log_name')
                            ->label('Type')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\TextInput::make('event')
                            ->label('Event')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\TextInput::make('description')
                            ->label('Description')
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('causer.name')
                            ->label('User')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\TextInput::make('subject_type')
                            ->label('Subject Type')
                            ->formatStateUsing(fn (?string $state): string => $state ? class_basename($state) : '-')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\TextInput::make('subject_id')
                            ->label('Subject ID')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\DateTimePicker::make('created_at')
                            ->label('Logged At')
                            ->native(false)
                            ->seconds(false)
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\Textarea::make('properties')
                            ->label('Properties')
                            ->formatStateUsing(function ($state): string {
                                if (blank($state)) {
                                    return '-';
                                }

                                if ($state instanceof \Illuminate\Support\Collection) {
                                    $state = $state->toArray();
                                }

                                return json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                            })
                            ->rows(8)
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('log_name')
                    ->label('Type')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('event')
                    ->label('Event')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'warning',
                        'deleted' => 'danger',
                        'login' => 'success',
                        'logout' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => $state ? ucfirst($state) : '-')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->searchable()
                    ->limit(70)
                    ->wrap(),

                Tables\Columns\TextColumn::make('subject_type')
                    ->label('Subject')
                    ->formatStateUsing(fn (?string $state): string => $state ? class_basename($state) : '-')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('subject_id')
                    ->label('Subject ID')
                    ->sortable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('causer.name')
                    ->label('User')
                    ->placeholder('System')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Logged At')
                    ->dateTime('d M Y H:i:s')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('log_name')
                    ->label('Type')
                    ->options(fn (): array => Activity::query()
                        ->whereNotNull('log_name')
                        ->distinct()
                        ->pluck('log_name', 'log_name')
                        ->toArray()),

                Tables\Filters\SelectFilter::make('event')
                    ->label('Event')
                    ->options(fn (): array => Activity::query()
                        ->whereNotNull('event')
                        ->distinct()
                        ->pluck('event', 'event')
                        ->toArray()),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat'),
            ])
            ->bulkActions([])
            ->defaultSort('created_at', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'causer',
                'subject',
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListActivityLogs::route('/'),
            'view' => ViewActivityLog::route('/{record}'),
        ];
    }
}