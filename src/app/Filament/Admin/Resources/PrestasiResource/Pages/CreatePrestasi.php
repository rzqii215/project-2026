<?php

namespace App\Filament\Admin\Resources\PrestasiResource\Pages;

use App\Filament\Admin\Resources\PrestasiResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePrestasi extends CreateRecord
{
    protected static string $resource = PrestasiResource::class;

    protected function getRedirectUrl(): string
    {
        return PrestasiResource::getUrl('index');
    }
}