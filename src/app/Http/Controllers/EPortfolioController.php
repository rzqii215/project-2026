<?php

namespace App\Http\Controllers;

use App\Models\Prestasi;
use App\Models\User;
use Illuminate\Contracts\View\View;

class EPortfolioController extends Controller
{
    public function show(User $user): View
    {
        $user->load([
            'profilMahasiswa',
        ]);

        $prestasis = $this->getPrestasiTerverifikasi($user);

        return view('eportfolio.show', [
            'user' => $user,
            'profil' => $user->profilMahasiswa,
            'prestasis' => $prestasis,
        ]);
    }

    public function cetak(User $user): View
    {
        $user->load([
            'profilMahasiswa',
        ]);

        $prestasis = $this->getPrestasiTerverifikasi($user);

        return view('eportfolio.print', [
            'user' => $user,
            'profil' => $user->profilMahasiswa,
            'prestasis' => $prestasis,
        ]);
    }

    public function print(User $user): View
    {
        return $this->cetak($user);
    }

    private function getPrestasiTerverifikasi(User $user)
    {
        return Prestasi::query()
            ->where('mahasiswa_id', $user->id)
            ->where('status', Prestasi::STATUS_DIVERIFIKASI)
            ->with([
                'kategoriPrestasi',
                'berkasPrestasis',
            ])
            ->orderByDesc('tanggal_prestasi')
            ->orderByDesc('created_at')
            ->get();
    }
}