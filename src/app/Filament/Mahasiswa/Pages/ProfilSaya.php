<?php

namespace App\Filament\Mahasiswa\Pages;

use App\Models\ProfilMahasiswa;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class ProfilSaya extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationLabel = 'Profil Saya';

    protected static ?string $navigationGroup = 'Akun';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.mahasiswa.pages.profil-saya';

    public ?array $data = [];

    public ?ProfilMahasiswa $profilMahasiswa = null;

    public function mount(): void
    {
        $user = Auth::user();

        $this->profilMahasiswa = ProfilMahasiswa::query()
            ->firstOrNew([
                'user_id' => $user?->id,
            ]);

        $this->form->fill([
            'name' => $user?->name,
            'email' => $user?->email,
            'nomor_hp' => $user?->nomor_hp,
            'nim' => $this->profilMahasiswa->nim,
            'program_studi' => $this->profilMahasiswa->program_studi,
            'fakultas' => $this->profilMahasiswa->fakultas,
            'angkatan' => $this->profilMahasiswa->angkatan,
            'alamat' => $this->profilMahasiswa->alamat,
            'foto' => $this->profilMahasiswa->foto,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Data Akun')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('email')
                            ->label('Email')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('nomor_hp')
                            ->label('Nomor HP')
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->columns(3),

                Section::make('Profil Akademik')
                    ->schema([
                        TextInput::make('nim')
                            ->label('NIM')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('program_studi')
                            ->label('Program Studi')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('fakultas')
                            ->label('Fakultas')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('angkatan')
                            ->label('Angkatan')
                            ->numeric()
                            ->minValue(2000)
                            ->maxValue(2100)
                            ->required(),

                        Textarea::make('alamat')
                            ->label('Alamat')
                            ->rows(4)
                            ->columnSpanFull(),

                        FileUpload::make('foto')
                            ->label('Foto Profil')
                            ->disk('public')
                            ->directory('foto-mahasiswa')
                            ->image()
                            ->imageEditor()
                            ->downloadable()
                            ->openable()
                            ->maxSize(2048)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    public function simpan(): void
    {
        $data = $this->form->getState();

        $this->profilMahasiswa = ProfilMahasiswa::query()->updateOrCreate(
            [
                'user_id' => Auth::id(),
            ],
            [
                'nim' => $data['nim'],
                'program_studi' => $data['program_studi'],
                'fakultas' => $data['fakultas'],
                'angkatan' => $data['angkatan'],
                'alamat' => $data['alamat'] ?? null,
                'foto' => $data['foto'] ?? null,
            ]
        );

        Notification::make()
            ->title('Profil berhasil disimpan')
            ->success()
            ->send();
    }
}