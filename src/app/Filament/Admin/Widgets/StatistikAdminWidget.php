<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Prestasi;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatistikAdminWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalMahasiswa = User::query()
            ->where('role', 'mahasiswa')
            ->count();

        $totalPrestasi = Prestasi::query()->count();

        $totalDiajukan = Prestasi::query()
            ->where('status', Prestasi::STATUS_DIAJUKAN)
            ->count();

        $totalDiverifikasi = Prestasi::query()
            ->where('status', Prestasi::STATUS_DIVERIFIKASI)
            ->count();

        $totalDitolak = Prestasi::query()
            ->where('status', Prestasi::STATUS_DITOLAK)
            ->count();

        return [
            Stat::make('Total Mahasiswa', $totalMahasiswa)
                ->description('Mahasiswa terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),

            Stat::make('Total Prestasi', $totalPrestasi)
                ->description('Semua data prestasi')
                ->descriptionIcon('heroicon-m-trophy')
                ->color('gray'),

            Stat::make('Menunggu Verifikasi', $totalDiajukan)
                ->description('Perlu diverifikasi admin')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Terverifikasi', $totalDiverifikasi)
                ->description('Prestasi valid')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Ditolak', $totalDitolak)
                ->description('Perlu diperbaiki mahasiswa')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
        ];
    }
}