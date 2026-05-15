<?php

namespace App\Filament\Admin\Resources\ProfilMahasiswaResource\Pages;

use App\Filament\Admin\Resources\ProfilMahasiswaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProfilMahasiswas extends ListRecords
{
    protected static string $resource = ProfilMahasiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
