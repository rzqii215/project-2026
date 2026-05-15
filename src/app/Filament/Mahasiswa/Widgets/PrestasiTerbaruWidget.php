<?php

namespace App\Filament\Mahasiswa\Widgets;

use App\Models\Prestasi;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class PrestasiTerbaruWidget extends TableWidget
{
    protected static ?string $heading = 'Prestasi Terbaru';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getPrestasiQuery())
            ->columns([
                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul Prestasi')
                    ->searchable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('kategoriPrestasi.nama')
                    ->label('Kategori')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('tingkat')
                    ->label('Tingkat')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $this->labelTingkat($state))
                    ->color(fn (string $state): string => $this->warnaTingkat($state)),

                Tables\Columns\TextColumn::make('tanggal_prestasi')
                    ->label('Tanggal Prestasi')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $this->labelStatus($state))
                    ->color(fn (string $state): string => $this->warnaStatus($state)),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Diajukan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([5, 10]);
    }

    protected function getPrestasiQuery(): Builder
    {
        return Prestasi::query()
            ->where('mahasiswa_id', Auth::id())
            ->with([
                'kategoriPrestasi',
            ]);
    }

    protected function labelStatus(string $status): string
    {
        return match ($status) {
            Prestasi::STATUS_DIAJUKAN => 'Diajukan',
            Prestasi::STATUS_DIVERIFIKASI => 'Diverifikasi',
            Prestasi::STATUS_DITOLAK => 'Ditolak',
            default => ucfirst(str_replace('_', ' ', $status)),
        };
    }

    protected function warnaStatus(string $status): string
    {
        return match ($status) {
            Prestasi::STATUS_DIAJUKAN => 'warning',
            Prestasi::STATUS_DIVERIFIKASI => 'success',
            Prestasi::STATUS_DITOLAK => 'danger',
            default => 'gray',
        };
    }

    protected function labelTingkat(string $tingkat): string
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

    protected function warnaTingkat(string $tingkat): string
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
}