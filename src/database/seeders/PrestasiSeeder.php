<?php

namespace Database\Seeders;

use App\Models\KategoriPrestasi;
use App\Models\Prestasi;
use App\Models\User;
use Illuminate\Database\Seeder;

class PrestasiSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@admin.com')->first();
        $raka = User::where('email', 'mahasiswa@demo.com')->first();
        $sinta = User::where('email', 'sinta@demo.com')->first();

        $akademik = KategoriPrestasi::where('nama', 'Akademik')->first();
        $teknologi = KategoriPrestasi::where('nama', 'Teknologi')->first();
        $organisasi = KategoriPrestasi::where('nama', 'Organisasi')->first();

        if ($raka && $akademik && $admin) {
            Prestasi::updateOrCreate(
                [
                    'mahasiswa_id' => $raka->id,
                    'judul' => 'Juara 1 Lomba Karya Tulis Ilmiah',
                ],
                [
                    'kategori_prestasi_id' => $akademik->id,
                    'penyelenggara' => 'Universitas Nasional',
                    'tingkat' => Prestasi::TINGKAT_NASIONAL,
                    'peringkat' => 'Juara 1',
                    'tanggal_prestasi' => now()->subMonths(2)->toDateString(),
                    'deskripsi' => 'Meraih juara pertama dalam lomba karya tulis ilmiah tingkat nasional.',
                    'status' => Prestasi::STATUS_DIVERIFIKASI,
                    'catatan_verifikasi' => 'Prestasi valid dan sertifikat sesuai.',
                    'diverifikasi_oleh' => $admin->id,
                    'diverifikasi_pada' => now()->subMonth(),
                ]
            );
        }

        if ($raka && $teknologi) {
            Prestasi::updateOrCreate(
                [
                    'mahasiswa_id' => $raka->id,
                    'judul' => 'Finalis Web Development Competition',
                ],
                [
                    'kategori_prestasi_id' => $teknologi->id,
                    'penyelenggara' => 'Himpunan Mahasiswa Informatika',
                    'tingkat' => Prestasi::TINGKAT_PROVINSI,
                    'peringkat' => 'Finalis',
                    'tanggal_prestasi' => now()->subWeeks(3)->toDateString(),
                    'deskripsi' => 'Masuk final kompetisi pengembangan aplikasi berbasis web.',
                    'status' => Prestasi::STATUS_DIAJUKAN,
                    'catatan_verifikasi' => null,
                    'diverifikasi_oleh' => null,
                    'diverifikasi_pada' => null,
                ]
            );
        }

        if ($sinta && $organisasi) {
            Prestasi::updateOrCreate(
                [
                    'mahasiswa_id' => $sinta->id,
                    'judul' => 'Ketua Pelaksana Seminar Nasional',
                ],
                [
                    'kategori_prestasi_id' => $organisasi->id,
                    'penyelenggara' => 'BEM Fakultas Teknologi Informasi',
                    'tingkat' => Prestasi::TINGKAT_KAMPUS,
                    'peringkat' => 'Ketua Pelaksana',
                    'tanggal_prestasi' => now()->subMonths(1)->toDateString(),
                    'deskripsi' => 'Berhasil menjadi ketua pelaksana kegiatan seminar nasional.',
                    'status' => Prestasi::STATUS_DIVERIFIKASI,
                    'catatan_verifikasi' => 'Prestasi organisasi valid.',
                    'diverifikasi_oleh' => $admin?->id,
                    'diverifikasi_pada' => now()->subWeeks(2),
                ]
            );
        }
    }
}