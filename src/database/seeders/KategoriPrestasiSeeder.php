<?php

namespace Database\Seeders;

use App\Models\KategoriPrestasi;
use Illuminate\Database\Seeder;

class KategoriPrestasiSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nama' => 'Akademik',
                'deskripsi' => 'Prestasi yang berkaitan dengan kegiatan akademik, lomba karya tulis, olimpiade, atau kompetisi keilmuan.',
            ],
            [
                'nama' => 'Non Akademik',
                'deskripsi' => 'Prestasi yang berkaitan dengan kegiatan non akademik, seni, budaya, olahraga, dan organisasi.',
            ],
            [
                'nama' => 'Organisasi',
                'deskripsi' => 'Prestasi atau penghargaan dalam kegiatan organisasi kampus maupun luar kampus.',
            ],
            [
                'nama' => 'Kewirausahaan',
                'deskripsi' => 'Prestasi yang berkaitan dengan kompetisi bisnis, startup, dan kewirausahaan.',
            ],
            [
                'nama' => 'Teknologi',
                'deskripsi' => 'Prestasi dalam bidang teknologi, pemrograman, desain aplikasi, dan inovasi digital.',
            ],
        ];

        foreach ($data as $item) {
            KategoriPrestasi::updateOrCreate(
                ['nama' => $item['nama']],
                [
                    'deskripsi' => $item['deskripsi'],
                    'aktif' => true,
                ]
            );
        }
    }
}