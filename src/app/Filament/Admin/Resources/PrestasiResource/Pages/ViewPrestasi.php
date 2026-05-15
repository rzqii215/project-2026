<?php

namespace App\Filament\Admin\Resources\PrestasiResource\Pages;

use App\Filament\Admin\Resources\PrestasiResource;
use App\Models\Notifikasi;
use App\Models\Prestasi;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;

class ViewPrestasi extends ViewRecord
{
    protected static string $resource = PrestasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('verifikasi')
                ->label('Verifikasi')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn (): bool => $this->record->status !== Prestasi::STATUS_DIVERIFIKASI)
                ->action(function (): void {
                    $this->record->update([
                        'status' => Prestasi::STATUS_DIVERIFIKASI,
                        'catatan_verifikasi' => null,
                        'diverifikasi_oleh' => Auth::id(),
                        'diverifikasi_pada' => now(),
                    ]);

                    Notifikasi::create([
                        'pengguna_id' => $this->record->mahasiswa_id,
                        'tipe' => 'prestasi_diverifikasi',
                        'judul' => 'Prestasi Diverifikasi',
                        'pesan' => 'Prestasi "' . $this->record->judul . '" telah diverifikasi oleh admin.',
                    ]);

                    Notification::make()
                        ->title('Prestasi berhasil diverifikasi')
                        ->success()
                        ->send();
                }),

            Actions\Action::make('tolak')
                ->label('Tolak')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn (): bool => $this->record->status !== Prestasi::STATUS_DITOLAK)
                ->form([
                    Forms\Components\Textarea::make('catatan_verifikasi')
                        ->label('Alasan Penolakan')
                        ->required()
                        ->rows(4),
                ])
                ->action(function (array $data): void {
                    $this->record->update([
                        'status' => Prestasi::STATUS_DITOLAK,
                        'catatan_verifikasi' => $data['catatan_verifikasi'],
                        'diverifikasi_oleh' => Auth::id(),
                        'diverifikasi_pada' => now(),
                    ]);

                    Notifikasi::create([
                        'pengguna_id' => $this->record->mahasiswa_id,
                        'tipe' => 'prestasi_ditolak',
                        'judul' => 'Prestasi Ditolak',
                        'pesan' => 'Prestasi "' . $this->record->judul . '" ditolak. Catatan: ' . $data['catatan_verifikasi'],
                    ]);

                    Notification::make()
                        ->title('Prestasi berhasil ditolak')
                        ->success()
                        ->send();
                }),

            Actions\EditAction::make()
                ->label('Edit'),
        ];
    }
}