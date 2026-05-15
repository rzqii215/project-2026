<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Prestasi;
use Filament\Widgets\ChartWidget;

class PrestasiPerTingkatChart extends ChartWidget
{
    protected static ?string $heading = 'Prestasi Berdasarkan Tingkat';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $kampus = Prestasi::query()
            ->where('tingkat', Prestasi::TINGKAT_KAMPUS)
            ->count();

        $kabupaten = Prestasi::query()
            ->where('tingkat', Prestasi::TINGKAT_KABUPATEN)
            ->count();

        $provinsi = Prestasi::query()
            ->where('tingkat', Prestasi::TINGKAT_PROVINSI)
            ->count();

        $nasional = Prestasi::query()
            ->where('tingkat', Prestasi::TINGKAT_NASIONAL)
            ->count();

        $internasional = Prestasi::query()
            ->where('tingkat', Prestasi::TINGKAT_INTERNASIONAL)
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Prestasi',
                    'data' => [
                        $kampus,
                        $kabupaten,
                        $provinsi,
                        $nasional,
                        $internasional,
                    ],
                ],
            ],
            'labels' => [
                'Kampus',
                'Kabupaten/Kota',
                'Provinsi',
                'Nasional',
                'Internasional',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}