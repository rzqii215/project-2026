<?php

namespace App\Filament\Admin\Resources\BerkasPrestasiResource\Pages;

use App\Filament\Admin\Resources\BerkasPrestasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBerkasPrestasis extends ListRecords
{
    protected static string $resource = BerkasPrestasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
