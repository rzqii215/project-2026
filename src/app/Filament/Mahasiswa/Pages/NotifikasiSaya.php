<?php

namespace App\Filament\Mahasiswa\Pages;

use App\Models\Notifikasi;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class NotifikasiSaya extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-bell-alert';

    protected static ?string $navigationLabel = 'Notifikasi Saya';

    protected static ?string $navigationGroup = 'Akun';

    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.mahasiswa.pages.notifikasi-saya';

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getQuery())
            ->columns([
                TextColumn::make('judul')
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tipe')
                    ->label('Tipe')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => $state ? ucwords(str_replace('_', ' ', $state)) : '-')
                    ->color('info'),

                TextColumn::make('pesan')
                    ->label('Pesan')
                    ->wrap()
                    ->limit(90),

                IconColumn::make('dibaca_pada')
                    ->label('Dibaca')
                    ->boolean()
                    ->getStateUsing(fn (Notifikasi $record): bool => filled($record->dibaca_pada)),

                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('belum_dibaca')
                    ->label('Belum Dibaca')
                    ->query(fn (Builder $query): Builder => $query->whereNull('dibaca_pada')),

                Tables\Filters\Filter::make('sudah_dibaca')
                    ->label('Sudah Dibaca')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('dibaca_pada')),
            ])
            ->headerActions([
                Action::make('tandai_semua_dibaca')
                    ->label('Tandai Semua Dibaca')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (): void {
                        Notifikasi::query()
                            ->where('pengguna_id', Auth::id())
                            ->whereNull('dibaca_pada')
                            ->update([
                                'dibaca_pada' => now(),
                            ]);

                        FilamentNotification::make()
                            ->title('Semua notifikasi ditandai sudah dibaca')
                            ->success()
                            ->send();
                    }),
            ])
            ->actions([
                Action::make('tandai_dibaca')
                    ->label('Tandai Dibaca')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Notifikasi $record): bool => blank($record->dibaca_pada))
                    ->action(function (Notifikasi $record): void {
                        $record->update([
                            'dibaca_pada' => now(),
                        ]);

                        FilamentNotification::make()
                            ->title('Notifikasi ditandai sudah dibaca')
                            ->success()
                            ->send();
                    }),

                DeleteAction::make()
                    ->label('Hapus'),
            ])
            ->defaultSort('created_at', 'desc');
    }

    protected function getQuery(): Builder
    {
        return Notifikasi::query()
            ->where('pengguna_id', Auth::id());
    }
}