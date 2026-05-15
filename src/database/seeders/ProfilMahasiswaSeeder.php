<?php

namespace Database\Seeders;

use App\Models\ProfilMahasiswa;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProfilMahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'email' => 'mahasiswa@demo.com',
                'nim' => '202601001',
                'program_studi' => 'Teknik Informatika',
                'fakultas' => 'Fakultas Teknologi Informasi',
                'angkatan' => 2026,
                'alamat' => 'Jl. Merdeka No. 10, Tangerang Selatan',
            ],
            [
                'email' => 'sinta@demo.com',
                'nim' => '202601002',
                'program_studi' => 'Sistem Informasi',
                'fakultas' => 'Fakultas Teknologi Informasi',
                'angkatan' => 2026,
                'alamat' => 'Jl. Melati No. 8, Jakarta Selatan',
            ],
        ];

        foreach ($data as $item) {
            $user = User::where('email', $item['email'])->first();

            if (! $user) {
                continue;
            }

            ProfilMahasiswa::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nim' => $item['nim'],
                    'program_studi' => $item['program_studi'],
                    'fakultas' => $item['fakultas'],
                    'angkatan' => $item['angkatan'],
                    'alamat' => $item['alamat'],
                    'foto' => null,
                ]
            );
        }
    }
}