<?php

namespace App\Filament\Mahasiswa\Widgets;

use App\Models\Notifikasi;
use App\Models\Prestasi;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class StatistikPrestasiWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $userId = Auth::id();

        $totalPrestasi = Prestasi::query()
            ->where('mahasiswa_id', $userId)
            ->count();

        $prestasiDiajukan = Prestasi::query()
            ->where('mahasiswa_id', $userId)
            ->where('status', Prestasi::STATUS_DIAJUKAN)
            ->count();

        $prestasiDiverifikasi = Prestasi::query()
            ->where('mahasiswa_id', $userId)
            ->where('status', Prestasi::STATUS_DIVERIFIKASI)
            ->count();

        $prestasiDitolak = Prestasi::query()
            ->where('mahasiswa_id', $userId)
            ->where('status', Prestasi::STATUS_DITOLAK)
            ->count();

        $notifikasiBelumDibaca = Notifikasi::query()
            ->where('pengguna_id', $userId)
            ->whereNull('dibaca_pada')
            ->count();

        return [
            Stat::make('Total Prestasi', $totalPrestasi)
                ->description('Seluruh prestasi yang diajukan')
                ->descriptionIcon('heroicon-m-trophy')
                ->color('info'),

            Stat::make('Diajukan', $prestasiDiajukan)
                ->description('Menunggu verifikasi admin')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Diverifikasi', $prestasiDiverifikasi)
                ->description('Prestasi sudah valid')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Ditolak', $prestasiDitolak)
                ->description('Perlu diperbaiki mahasiswa')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),

            Stat::make('Notifikasi Baru', $notifikasiBelumDibaca)
                ->description('Belum dibaca')
                ->descriptionIcon('heroicon-m-bell-alert')
                ->color('primary'),
        ];
    }
}