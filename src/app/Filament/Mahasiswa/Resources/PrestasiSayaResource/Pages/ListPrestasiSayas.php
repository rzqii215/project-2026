<?php

namespace App\Filament\Mahasiswa\Resources\PrestasiSayaResource\Pages;

use App\Filament\Mahasiswa\Resources\PrestasiSayaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPrestasiSayas extends ListRecords
{
    protected static string $resource = PrestasiSayaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Prestasi'),
        ];
    }
}