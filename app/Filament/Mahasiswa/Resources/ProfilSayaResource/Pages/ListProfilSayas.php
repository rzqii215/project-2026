<?php

namespace App\Filament\Mahasiswa\Resources\ProfilSayaResource\Pages;

use App\Filament\Mahasiswa\Resources\ProfilSayaResource;
use App\Models\ProfilMahasiswa;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListProfilSayas extends ListRecords
{
    protected static string $resource = ProfilSayaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Lengkapi Profil')
                ->visible(fn (): bool => ! ProfilMahasiswa::query()
                    ->where('user_id', Auth::id())
                    ->exists()),
        ];
    }
}
