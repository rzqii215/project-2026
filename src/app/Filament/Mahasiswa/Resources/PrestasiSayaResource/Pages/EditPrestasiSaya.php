<?php

namespace App\Filament\Mahasiswa\Resources\PrestasiSayaResource\Pages;

use App\Filament\Mahasiswa\Resources\PrestasiSayaResource;
use App\Models\Notifikasi;
use App\Models\Prestasi;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditPrestasiSaya extends EditRecord
{
    protected static string $resource = PrestasiSayaResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($this->record->status === Prestasi::STATUS_DITOLAK) {
            $data['status'] = Prestasi::STATUS_DIAJUKAN;
            $data['catatan_verifikasi'] = null;
            $data['diverifikasi_oleh'] = null;
            $data['diverifikasi_pada'] = null;
        }

        return $data;
    }

    protected function afterSave(): void
    {
        if ($this->record->status === Prestasi::STATUS_DIAJUKAN) {
            Notifikasi::create([
                'pengguna_id' => Auth::id(),
                'tipe' => 'prestasi_diperbarui',
                'judul' => 'Prestasi Diperbarui',
                'pesan' => 'Prestasi "' . $this->record->judul . '" berhasil diperbarui dan menunggu verifikasi admin.',
            ]);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}