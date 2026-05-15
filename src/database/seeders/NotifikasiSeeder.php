<?php

namespace Database\Seeders;

use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotifikasiSeeder extends Seeder
{
    public function run(): void
    {
        $raka = User::where('email', 'mahasiswa@demo.com')->first();
        $sinta = User::where('email', 'sinta@demo.com')->first();

        if ($raka) {
            Notifikasi::create([
                'pengguna_id' => $raka->id,
                'tipe' => 'prestasi_diverifikasi',
                'judul' => 'Prestasi Diverifikasi',
                'pesan' => 'Prestasi "Juara 1 Lomba Karya Tulis Ilmiah" telah diverifikasi oleh admin.',
                'dibaca_pada' => null,
            ]);

            Notifikasi::create([
                'pengguna_id' => $raka->id,
                'tipe' => 'prestasi_diajukan',
                'judul' => 'Prestasi Diajukan',
                'pesan' => 'Prestasi "Finalis Web Development Competition" sedang menunggu verifikasi admin.',
                'dibaca_pada' => null,
            ]);
        }

        if ($sinta) {
            Notifikasi::create([
                'pengguna_id' => $sinta->id,
                'tipe' => 'prestasi_diverifikasi',
                'judul' => 'Prestasi Diverifikasi',
                'pesan' => 'Prestasi "Ketua Pelaksana Seminar Nasional" telah diverifikasi oleh admin.',
                'dibaca_pada' => null,
            ]);
        }
    }
}