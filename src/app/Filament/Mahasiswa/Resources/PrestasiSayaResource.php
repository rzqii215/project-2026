<?php

namespace App\Filament\Mahasiswa\Resources;

use App\Filament\Mahasiswa\Resources\PrestasiSayaResource\Pages\CreatePrestasiSaya;
use App\Filament\Mahasiswa\Resources\PrestasiSayaResource\Pages\EditPrestasiSaya;
use App\Filament\Mahasiswa\Resources\PrestasiSayaResource\Pages\ListPrestasiSayas;
use App\Filament\Mahasiswa\Resources\PrestasiSayaResource\Pages\ViewPrestasiSaya;
use App\Models\KategoriPrestasi;
use App\Models\Prestasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class PrestasiSayaResource extends Resource
{
    protected static ?string $model = Prestasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';

    protected static ?string $navigationLabel = 'Prestasi Saya';

    protected static ?string $navigationGroup = 'E-Portfolio';

    protected static ?int $navigationSort = 1;

    public static function getModelLabel(): string
    {
        return 'Prestasi Saya';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Prestasi Saya';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Prestasi')
                    ->schema([
                        Forms\Components\Select::make('kategori_prestasi_id')
                            ->label('Kategori Prestasi')
                            ->options(fn (): array => KategoriPrestasi::query()
                                ->where('aktif', true)
                                ->orderBy('nama')
                                ->pluck('nama', 'id')
                                ->toArray())
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\TextInput::make('judul')
                            ->label('Judul Prestasi')
                            ->placeholder('Contoh: Juara 1 Lomba Web Design')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('penyelenggara')
                            ->label('Penyelenggara')
                            ->placeholder('Contoh: Universitas Nasional')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('tingkat')
                            ->label('Tingkat Prestasi')
                            ->options([
                                Prestasi::TINGKAT_KAMPUS => 'Kampus',
                                Prestasi::TINGKAT_KABUPATEN => 'Kabupaten/Kota',
                                Prestasi::TINGKAT_PROVINSI => 'Provinsi',
                                Prestasi::TINGKAT_NASIONAL => 'Nasional',
                                Prestasi::TINGKAT_INTERNASIONAL => 'Internasional',
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('peringkat')
                            ->label('Peringkat / Capaian')
                            ->placeholder('Contoh: Juara 1, Finalis, Peserta Terbaik')
                            ->maxLength(255),

                        Forms\Components\DatePicker::make('tanggal_prestasi')
                            ->label('Tanggal Prestasi')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->required(),

                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi Prestasi')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Berkas Pendukung')
                    ->schema([
                        Forms\Components\Repeater::make('berkasPrestasis')
                            ->label('Berkas Prestasi')
                            ->relationship('berkasPrestasis')
                            ->schema([
                                Forms\Components\TextInput::make('nama_file')
                                    ->label('Nama File')
                                    ->placeholder('Contoh: Sertifikat Juara')
                                    ->required()
                                    ->maxLength(255),

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

                                Forms\Components\FileUpload::make('path_file')
                                    ->label('Upload File')
                                    ->directory('berkas-prestasi')
                                    ->acceptedFileTypes([
                                        'application/pdf',
                                        'image/jpeg',
                                        'image/png',
                                        'image/jpg',
                                    ])
                                    ->maxSize(4096)
                                    ->downloadable()
                                    ->openable()
                                    ->required(),
                            ])
                            ->columns(3)
                            ->defaultItems(1)
                            ->addActionLabel('Tambah Berkas')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Status Verifikasi')
                    ->schema([
                        Forms\Components\TextInput::make('status')
                            ->label('Status')
                            ->formatStateUsing(fn (?string $state): string => self::labelStatus($state ?? Prestasi::STATUS_DIAJUKAN))
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\Textarea::make('catatan_verifikasi')
                            ->label('Catatan Verifikasi')
                            ->rows(3)
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\TextInput::make('verifier.name')
                            ->label('Diverifikasi Oleh')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\DateTimePicker::make('diverifikasi_pada')
                            ->label('Diverifikasi Pada')
                            ->native(false)
                            ->seconds(false)
                            ->displayFormat('d/m/Y H:i')
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->columns(2)
                    ->visibleOn('edit'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul Prestasi')
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('kategoriPrestasi.nama')
                    ->label('Kategori')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('tingkat')
                    ->label('Tingkat')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => self::labelTingkat($state))
                    ->color(fn (string $state): string => self::warnaTingkat($state)),

                Tables\Columns\TextColumn::make('peringkat')
                    ->label('Peringkat')
                    ->placeholder('-')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tanggal_prestasi')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => self::labelStatus($state))
                    ->color(fn (string $state): string => self::warnaStatus($state)),

                Tables\Columns\TextColumn::make('berkasPrestasis_count')
                    ->label('Berkas')
                    ->counts('berkasPrestasis')
                    ->suffix(' file'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Diajukan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        Prestasi::STATUS_DIAJUKAN => 'Diajukan',
                        Prestasi::STATUS_DIVERIFIKASI => 'Diverifikasi',
                        Prestasi::STATUS_DITOLAK => 'Ditolak',
                    ]),

                Tables\Filters\SelectFilter::make('kategori_prestasi_id')
                    ->label('Kategori')
                    ->relationship('kategoriPrestasi', 'nama'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat'),

                Tables\Actions\EditAction::make()
                    ->label('Edit')
                    ->visible(fn (Prestasi $record): bool => $record->status !== Prestasi::STATUS_DIVERIFIKASI),

                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->visible(fn (Prestasi $record): bool => $record->status === Prestasi::STATUS_DIAJUKAN),
            ])
            ->bulkActions([])
            ->defaultSort('created_at', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('mahasiswa_id', Auth::id())
            ->with([
                'kategoriPrestasi',
                'verifier',
                'berkasPrestasis',
            ]);
    }

    public static function labelStatus(string $status): string
    {
        return match ($status) {
            Prestasi::STATUS_DIAJUKAN => 'Diajukan',
            Prestasi::STATUS_DIVERIFIKASI => 'Diverifikasi',
            Prestasi::STATUS_DITOLAK => 'Ditolak',
            default => ucfirst(str_replace('_', ' ', $status)),
        };
    }

    public static function warnaStatus(string $status): string
    {
        return match ($status) {
            Prestasi::STATUS_DIAJUKAN => 'warning',
            Prestasi::STATUS_DIVERIFIKASI => 'success',
            Prestasi::STATUS_DITOLAK => 'danger',
            default => 'gray',
        };
    }

    public static function labelTingkat(string $tingkat): string
    {
        return match ($tingkat) {
            Prestasi::TINGKAT_KAMPUS => 'Kampus',
            Prestasi::TINGKAT_KABUPATEN => 'Kabupaten/Kota',
            Prestasi::TINGKAT_PROVINSI => 'Provinsi',
            Prestasi::TINGKAT_NASIONAL => 'Nasional',
            Prestasi::TINGKAT_INTERNASIONAL => 'Internasional',
            default => ucfirst(str_replace('_', ' ', $tingkat)),
        };
    }

    public static function warnaTingkat(string $tingkat): string
    {
        return match ($tingkat) {
            Prestasi::TINGKAT_KAMPUS => 'gray',
            Prestasi::TINGKAT_KABUPATEN => 'info',
            Prestasi::TINGKAT_PROVINSI => 'warning',
            Prestasi::TINGKAT_NASIONAL => 'success',
            Prestasi::TINGKAT_INTERNASIONAL => 'danger',
            default => 'gray',
        };
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPrestasiSayas::route('/'),
            'create' => CreatePrestasiSaya::route('/create'),
            'view' => ViewPrestasiSaya::route('/{record}'),
            'edit' => EditPrestasiSaya::route('/{record}/edit'),
        ];
    }
}