<?php

namespace App\Filament\Mahasiswa\Resources\ProfilSayaResource\Pages;

use App\Filament\Mahasiswa\Resources\ProfilSayaResource;
use Filament\Resources\Pages\EditRecord;

class EditProfilSaya extends EditRecord
{
    protected static string $resource = ProfilSayaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
