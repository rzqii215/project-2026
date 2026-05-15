<?php

namespace App\Filament\Mahasiswa\Resources\ProfilSayaResource\Pages;

use App\Filament\Mahasiswa\Resources\ProfilSayaResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateProfilSaya extends CreateRecord
{
    protected static string $resource = ProfilSayaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
