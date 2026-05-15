<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BerkasPrestasiResource\Pages;
use App\Models\BerkasPrestasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BerkasPrestasiResource extends Resource
{
    protected static ?string $model = BerkasPrestasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-up';

    protected static ?string $navigationGroup = 'Prestasi';

    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return 'Berkas Prestasi';
    }

    public static function getModelLabel(): string
    {
        return 'Berkas Prestasi';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Berkas Prestasi';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Berkas')
                    ->schema([
                        Forms\Components\Select::make('prestasi_id')
                            ->label('Prestasi')
                            ->relationship('prestasi', 'judul')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\TextInput::make('nama_file')
                            ->label('Nama File')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\FileUpload::make('path_file')
                            ->label('File Sertifikat / Bukti')
                            ->directory('berkas-prestasi')
                            ->acceptedFileTypes([
                                'application/pdf',
                                'image/jpeg',
                                'image/png',
                                'image/jpg',
                            ])
                            ->maxSize(4096)
                            ->required(),

                        Forms\Components\Select::make('tipe_file')
                            ->label('Tipe File')
                            ->options([
                                'sertifikat' => 'Sertifikat',
                                'piagam' => 'Piagam',
                                'surat_tugas' => 'Surat Tugas',
                                'dokumentasi' => 'Dokumentasi',
                                'lainnya' => 'Lainnya',
                            ])
                            ->default('sertifikat')
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('prestasi.mahasiswa.name')
                    ->label('Mahasiswa')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('prestasi.judul')
                    ->label('Prestasi')
                    ->searchable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('nama_file')
                    ->label('Nama File')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tipe_file')
                    ->label('Tipe')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => $state ? ucwords(str_replace('_', ' ', $state)) : '-')
                    ->color('info'),

                Tables\Columns\TextColumn::make('path_file')
                    ->label('File')
                    ->formatStateUsing(fn (): string => 'Lihat File')
                    ->url(fn (BerkasPrestasi $record): string => asset('storage/' . $record->path_file))
                    ->openUrlInNewTab(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Diupload')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
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
            'index' => Pages\ListBerkasPrestasis::route('/'),
            'create' => Pages\CreateBerkasPrestasi::route('/create'),
            'edit' => Pages\EditBerkasPrestasi::route('/{record}/edit'),
        ];
    }
}