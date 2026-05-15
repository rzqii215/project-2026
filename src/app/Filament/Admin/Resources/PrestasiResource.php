<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PrestasiResource\Pages\CreatePrestasi;
use App\Filament\Admin\Resources\PrestasiResource\Pages\EditPrestasi;
use App\Filament\Admin\Resources\PrestasiResource\Pages\ListPrestasis;
use App\Filament\Admin\Resources\PrestasiResource\Pages\ViewPrestasi;
use App\Models\Notifikasi;
use App\Models\Prestasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class PrestasiResource extends Resource
{
    protected static ?string $model = Prestasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';

    protected static ?string $navigationLabel = 'Verifikasi Prestasi';

    protected static ?string $navigationGroup = 'Manajemen E-Portfolio';

    protected static ?int $navigationSort = 2;

    public static function getModelLabel(): string
    {
        return 'Prestasi';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Prestasi';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Mahasiswa')
                    ->schema([
                        Forms\Components\TextInput::make('mahasiswa.name')
                            ->label('Nama Mahasiswa')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\TextInput::make('mahasiswa.email')
                            ->label('Email')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\TextInput::make('mahasiswa.profilMahasiswa.nim')
                            ->label('NIM')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\TextInput::make('mahasiswa.profilMahasiswa.program_studi')
                            ->label('Program Studi')
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Informasi Prestasi')
                    ->schema([
                        Forms\Components\TextInput::make('judul')
                            ->label('Judul Prestasi')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('penyelenggara')
                            ->label('Penyelenggara')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('kategori_prestasi_id')
                            ->label('Kategori Prestasi')
                            ->relationship('kategoriPrestasi', 'nama')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('tingkat')
                            ->label('Tingkat')
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
                            ->maxLength(255),

                        Forms\Components\DatePicker::make('tanggal_prestasi')
                            ->label('Tanggal Prestasi')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->required(),

                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Berkas Prestasi')
                    ->schema([
                        Forms\Components\Repeater::make('berkasPrestasis')
                            ->label('Berkas')
                            ->relationship('berkasPrestasis')
                            ->schema([
                                Forms\Components\TextInput::make('nama_file')
                                    ->label('Nama File')
                                    ->disabled()
                                    ->dehydrated(false),

                                Forms\Components\TextInput::make('tipe_file')
                                    ->label('Tipe File')
                                    ->disabled()
                                    ->dehydrated(false),

                                Forms\Components\FileUpload::make('path_file')
                                    ->label('File')
                                    ->disk('public')
                                    ->directory('berkas-prestasi')
                                    ->downloadable()
                                    ->openable()
                                    ->disabled()
                                    ->dehydrated(false),
                            ])
                            ->columns(3)
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Verifikasi')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                Prestasi::STATUS_DIAJUKAN => 'Diajukan',
                                Prestasi::STATUS_DIVERIFIKASI => 'Diverifikasi',
                                Prestasi::STATUS_DITOLAK => 'Ditolak',
                            ])
                            ->required(),

                        Forms\Components\Textarea::make('catatan_verifikasi')
                            ->label('Catatan Verifikasi')
                            ->rows(4)
                            ->columnSpanFull(),

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
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('mahasiswa.name')
                    ->label('Mahasiswa')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('mahasiswa.profilMahasiswa.nim')
                    ->label('NIM')
                    ->searchable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul Prestasi')
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('kategoriPrestasi.nama')
                    ->label('Kategori')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('tingkat')
                    ->label('Tingkat')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => self::labelTingkat($state))
                    ->color(fn (string $state): string => self::warnaTingkat($state)),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => self::labelStatus($state))
                    ->color(fn (string $state): string => self::warnaStatus($state)),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Diajukan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('diverifikasi_pada')
                    ->label('Diverifikasi')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->placeholder('-'),
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

                Tables\Filters\SelectFilter::make('tingkat')
                    ->label('Tingkat')
                    ->options([
                        Prestasi::TINGKAT_KAMPUS => 'Kampus',
                        Prestasi::TINGKAT_KABUPATEN => 'Kabupaten/Kota',
                        Prestasi::TINGKAT_PROVINSI => 'Provinsi',
                        Prestasi::TINGKAT_NASIONAL => 'Nasional',
                        Prestasi::TINGKAT_INTERNASIONAL => 'Internasional',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat'),

                Tables\Actions\Action::make('verifikasi')
                    ->label('Verifikasi')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Prestasi $record): bool => $record->status !== Prestasi::STATUS_DIVERIFIKASI)
                    ->action(function (Prestasi $record): void {
                        $record->update([
                            'status' => Prestasi::STATUS_DIVERIFIKASI,
                            'catatan_verifikasi' => null,
                            'diverifikasi_oleh' => Auth::id(),
                            'diverifikasi_pada' => now(),
                        ]);

                        Notifikasi::create([
                            'pengguna_id' => $record->mahasiswa_id,
                            'tipe' => 'prestasi_diverifikasi',
                            'judul' => 'Prestasi Diverifikasi',
                            'pesan' => 'Prestasi "' . $record->judul . '" telah diverifikasi oleh admin.',
                        ]);

                        Notification::make()
                            ->title('Prestasi berhasil diverifikasi')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('tolak')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Prestasi $record): bool => $record->status !== Prestasi::STATUS_DITOLAK)
                    ->form([
                        Forms\Components\Textarea::make('catatan_verifikasi')
                            ->label('Alasan Penolakan')
                            ->required()
                            ->rows(4),
                    ])
                    ->action(function (Prestasi $record, array $data): void {
                        $record->update([
                            'status' => Prestasi::STATUS_DITOLAK,
                            'catatan_verifikasi' => $data['catatan_verifikasi'],
                            'diverifikasi_oleh' => Auth::id(),
                            'diverifikasi_pada' => now(),
                        ]);

                        Notifikasi::create([
                            'pengguna_id' => $record->mahasiswa_id,
                            'tipe' => 'prestasi_ditolak',
                            'judul' => 'Prestasi Ditolak',
                            'pesan' => 'Prestasi "' . $record->judul . '" ditolak. Catatan: ' . $data['catatan_verifikasi'],
                        ]);

                        Notification::make()
                            ->title('Prestasi berhasil ditolak')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\EditAction::make()
                    ->label('Edit'),

                Tables\Actions\DeleteAction::make()
                    ->label('Hapus'),
            ])
            ->bulkActions([])
            ->defaultSort('created_at', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'mahasiswa',
                'mahasiswa.profilMahasiswa',
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
            'index' => ListPrestasis::route('/'),
            'create' => CreatePrestasi::route('/create'),
            'view' => ViewPrestasi::route('/{record}'),
            'edit' => EditPrestasi::route('/{record}/edit'),
        ];
    }
}