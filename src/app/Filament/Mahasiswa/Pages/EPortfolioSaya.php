<?php

namespace App\Filament\Mahasiswa\Pages;

use App\Models\Prestasi;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class EPortfolioSaya extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationLabel = 'E-Portfolio Saya';

    protected static ?string $navigationGroup = 'E-Portfolio';

    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.mahasiswa.pages.e-portfolio-saya';

    public function getPublicUrl(): string
    {
        return route('eportfolio.show', Auth::id());
    }

    public function getPrintUrl(): string
    {
        return route('eportfolio.print', Auth::id());
    }

    public function getTotalPrestasiTerverifikasi(): int
    {
        return Prestasi::query()
            ->where('mahasiswa_id', Auth::id())
            ->where('status', Prestasi::STATUS_DIVERIFIKASI)
            ->count();
    }

    public function getTotalPrestasiDiajukan(): int
    {
        return Prestasi::query()
            ->where('mahasiswa_id', Auth::id())
            ->where('status', Prestasi::STATUS_DIAJUKAN)
            ->count();
    }

    public function getTotalPrestasiDitolak(): int
    {
        return Prestasi::query()
            ->where('mahasiswa_id', Auth::id())
            ->where('status', Prestasi::STATUS_DITOLAK)
            ->count();
    }
}