<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ProfilMahasiswaResource\Pages;
use App\Models\ProfilMahasiswa;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProfilMahasiswaResource extends Resource
{
    protected static ?string $model = ProfilMahasiswa::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return 'Profil Mahasiswa';
    }

    public static function getModelLabel(): string
    {
        return 'Profil Mahasiswa';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Profil Mahasiswa';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Akun')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Mahasiswa')
                            ->options(function (?ProfilMahasiswa $record): array {
                                return User::query()
                                    ->where('role', User::ROLE_MAHASISWA)
                                    ->when(
                                        $record,
                                        fn ($query) => $query->where(function ($query) use ($record): void {
                                            $query
                                                ->whereDoesntHave('profilMahasiswa')
                                                ->orWhere('id', $record->user_id);
                                        }),
                                        fn ($query) => $query->whereDoesntHave('profilMahasiswa')
                                    )
                                    ->orderBy('name')
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabledOn('edit'),
                    ]),

                Forms\Components\Section::make('Profil Akademik')
                    ->schema([
                        Forms\Components\TextInput::make('nim')
                            ->label('NIM')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\TextInput::make('program_studi')
                            ->label('Program Studi')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('fakultas')
                            ->label('Fakultas')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('angkatan')
                            ->label('Angkatan')
                            ->numeric()
                            ->minValue(2000)
                            ->maxValue(2100)
                            ->required(),

                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat')
                            ->rows(4)
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('foto')
                            ->label('Foto Mahasiswa')
                            ->directory('foto-mahasiswa')
                            ->image()
                            ->imageEditor()
                            ->maxSize(2048)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('foto')
                    ->label('Foto')
                    ->disk('public')
                    ->circular()
                    ->height(45)
                    ->width(45),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nim')
                    ->label('NIM')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('program_studi')
                    ->label('Program Studi')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('fakultas')
                    ->label('Fakultas')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('angkatan')
                    ->label('Angkatan')
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('user.nomor_hp')
                    ->label('Nomor HP')
                    ->searchable()
                    ->toggleable(),
            ])
            ->actions([
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
            'index' => Pages\ListProfilMahasiswas::route('/'),
            'create' => Pages\CreateProfilMahasiswa::route('/create'),
            'edit' => Pages\EditProfilMahasiswa::route('/{record}/edit'),
        ];
    }
}