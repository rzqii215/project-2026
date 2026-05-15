<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\NotifikasiResource\Pages;
use App\Models\Notifikasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NotifikasiResource extends Resource
{
    protected static ?string $model = Notifikasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell-alert';

    protected static ?string $navigationGroup = 'Sistem';

    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return 'Notifikasi';
    }

    public static function getModelLabel(): string
    {
        return 'Notifikasi';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Notifikasi';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Notifikasi')
                    ->schema([
                        Forms\Components\Select::make('pengguna_id')
                            ->label('Pengguna')
                            ->relationship('pengguna', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\TextInput::make('tipe')
                            ->label('Tipe')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('judul')
                            ->label('Judul')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('pesan')
                            ->label('Pesan')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),

                        Forms\Components\DateTimePicker::make('dibaca_pada')
                            ->label('Dibaca Pada')
                            ->native(false)
                            ->seconds(false)
                            ->displayFormat('d/m/Y H:i')
                            ->nullable(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pengguna.name')
                    ->label('Pengguna')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tipe')
                    ->label('Tipe')
                    ->badge()
                    ->searchable()
                    ->color('info'),

                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul')
                    ->searchable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('pesan')
                    ->label('Pesan')
                    ->limit(60),

                Tables\Columns\IconColumn::make('dibaca_pada')
                    ->label('Dibaca')
                    ->boolean()
                    ->getStateUsing(fn (Notifikasi $record): bool => filled($record->dibaca_pada)),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('tandai_dibaca')
                    ->label('Tandai Dibaca')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Notifikasi $record): bool => blank($record->dibaca_pada))
                    ->action(fn (Notifikasi $record) => $record->tandaiDibaca()),

                Tables\Actions\EditAction::make()
                    ->label('Edit'),

                Tables\Actions\DeleteAction::make()
                    ->label('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus Terpilih'),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNotifikasis::route('/'),
            'create' => Pages\CreateNotifikasi::route('/create'),
            'edit' => Pages\EditNotifikasi::route('/{record}/edit'),
        ];
    }
}