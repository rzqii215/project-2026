<?php

namespace App\Filament\Mahasiswa\Resources\PrestasiSayaResource\Pages;

use App\Filament\Mahasiswa\Resources\PrestasiSayaResource;
use App\Models\Prestasi;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPrestasiSaya extends ViewRecord
{
    protected static string $resource = PrestasiSayaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Edit')
                ->visible(fn (): bool => $this->record->status !== Prestasi::STATUS_DIVERIFIKASI),
        ];
    }
}