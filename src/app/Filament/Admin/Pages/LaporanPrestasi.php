<?php

namespace App\Filament\Admin\Pages;

use App\Models\KategoriPrestasi;
use App\Models\Prestasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;

class LaporanPrestasi extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static ?string $navigationLabel = 'Laporan Prestasi';

    protected static ?string $navigationGroup = 'Manajemen E-Portfolio';

    protected static ?int $navigationSort = 10;

    protected static string $view = 'filament.admin.pages.laporan-prestasi';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'status' => null,
            'tingkat' => null,
            'kategori_prestasi_id' => null,
            'tanggal_mulai' => null,
            'tanggal_selesai' => null,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Filter Laporan')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                Prestasi::STATUS_DIAJUKAN => 'Diajukan',
                                Prestasi::STATUS_DIVERIFIKASI => 'Diverifikasi',
                                Prestasi::STATUS_DITOLAK => 'Ditolak',
                            ])
                            ->placeholder('Semua Status'),

                        Forms\Components\Select::make('tingkat')
                            ->label('Tingkat')
                            ->options([
                                Prestasi::TINGKAT_KAMPUS => 'Kampus',
                                Prestasi::TINGKAT_KABUPATEN => 'Kabupaten/Kota',
                                Prestasi::TINGKAT_PROVINSI => 'Provinsi',
                                Prestasi::TINGKAT_NASIONAL => 'Nasional',
                                Prestasi::TINGKAT_INTERNASIONAL => 'Internasional',
                            ])
                            ->placeholder('Semua Tingkat'),

                        Forms\Components\Select::make('kategori_prestasi_id')
                            ->label('Kategori')
                            ->options(fn (): array => KategoriPrestasi::query()
                                ->orderBy('nama')
                                ->pluck('nama', 'id')
                                ->toArray())
                            ->searchable()
                            ->placeholder('Semua Kategori'),

                        Forms\Components\DatePicker::make('tanggal_mulai')
                            ->label('Tanggal Mulai')
                            ->native(false),

                        Forms\Components\DatePicker::make('tanggal_selesai')
                            ->label('Tanggal Selesai')
                            ->native(false),
                    ])
                    ->columns(3),
            ])
            ->statePath('data');
    }

    public function getCetakUrl(): string
    {
        return route('laporan.prestasi.cetak', $this->cleanFilter());
    }

    public function getExportUrl(): string
    {
        return route('laporan.prestasi.export', $this->cleanFilter());
    }

    public function getTotalPrestasi(): int
    {
        return $this->query()->count();
    }

    public function getTotalDiajukan(): int
    {
        return $this->query()
            ->where('status', Prestasi::STATUS_DIAJUKAN)
            ->count();
    }

    public function getTotalDiverifikasi(): int
    {
        return $this->query()
            ->where('status', Prestasi::STATUS_DIVERIFIKASI)
            ->count();
    }

    public function getTotalDitolak(): int
    {
        return $this->query()
            ->where('status', Prestasi::STATUS_DITOLAK)
            ->count();
    }

    private function query()
    {
        $filters = $this->cleanFilter();

        return Prestasi::query()
            ->when($filters['status'] ?? null, fn ($query, $value) => $query->where('status', $value))
            ->when($filters['tingkat'] ?? null, fn ($query, $value) => $query->where('tingkat', $value))
            ->when($filters['kategori_prestasi_id'] ?? null, fn ($query, $value) => $query->where('kategori_prestasi_id', $value))
            ->when($filters['tanggal_mulai'] ?? null, fn ($query, $value) => $query->whereDate('tanggal_prestasi', '>=', $value))
            ->when($filters['tanggal_selesai'] ?? null, fn ($query, $value) => $query->whereDate('tanggal_prestasi', '<=', $value));
    }

    private function cleanFilter(): array
    {
        return collect($this->data ?? [])
            ->filter(fn ($value) => filled($value))
            ->toArray();
    }
}