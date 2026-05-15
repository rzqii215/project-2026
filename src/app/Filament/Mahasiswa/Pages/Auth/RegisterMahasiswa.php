<?php

namespace App\Filament\Mahasiswa\Pages\Auth;

use App\Models\ProfilMahasiswa;
use App\Models\User;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Register;
use Illuminate\Database\Eloquent\Model;

class RegisterMahasiswa extends Register
{
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        Section::make('Data Akun')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama Lengkap')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->required()
                                    ->unique(User::class, 'email')
                                    ->maxLength(255),

                                TextInput::make('nomor_hp')
                                    ->label('Nomor HP')
                                    ->tel()
                                    ->maxLength(30),

                                TextInput::make('password')
                                    ->label('Password')
                                    ->password()
                                    ->required()
                                    ->confirmed()
                                    ->minLength(8),

                                TextInput::make('password_confirmation')
                                    ->label('Konfirmasi Password')
                                    ->password()
                                    ->required(),
                            ])
                            ->columns(2),

                        Section::make('Data Mahasiswa')
                            ->schema([
                                TextInput::make('nim')
                                    ->label('NIM')
                                    ->required()
                                    ->maxLength(50),

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
                                    ->required()
                                    ->minValue(2000)
                                    ->maxValue((int) now()->format('Y')),
                            ])
                            ->columns(2),
                    ])
                    ->statePath('data')
            ),
        ];
    }

    protected function handleRegistration(array $data): Model
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'nomor_hp' => $data['nomor_hp'] ?? null,
            'password' => $data['password'],
            'role' => User::ROLE_MAHASISWA,
        ]);

        ProfilMahasiswa::create([
            'user_id' => $user->id,
            'nim' => $data['nim'],
            'program_studi' => $data['program_studi'],
            'fakultas' => $data['fakultas'],
            'angkatan' => $data['angkatan'],
        ]);

        if (method_exists($user, 'assignRole')) {
            $user->assignRole('mahasiswa');
        }

        return $user;
    }
}