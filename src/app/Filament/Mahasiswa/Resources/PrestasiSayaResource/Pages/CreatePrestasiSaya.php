<?php

namespace App\Filament\Mahasiswa\Resources\PrestasiSayaResource\Pages;

use App\Filament\Mahasiswa\Resources\PrestasiSayaResource;
use App\Models\Notifikasi;
use App\Models\Prestasi;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreatePrestasiSaya extends CreateRecord
{
    protected static string $resource = PrestasiSayaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['mahasiswa_id'] = Auth::id();
        $data['status'] = Prestasi::STATUS_DIAJUKAN;
        $data['catatan_verifikasi'] = null;
        $data['diverifikasi_oleh'] = null;
        $data['diverifikasi_pada'] = null;

        return $data;
    }

    protected function afterCreate(): void
    {
        Notifikasi::create([
            'pengguna_id' => Auth::id(),
            'tipe' => 'prestasi_diajukan',
            'judul' => 'Prestasi Diajukan',
            'pesan' => 'Prestasi "' . $this->record->judul . '" berhasil diajukan dan sedang menunggu verifikasi admin.',
        ]);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}