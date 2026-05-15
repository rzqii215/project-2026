<?php

namespace App\Http\Controllers;

use App\Models\KategoriPrestasi;
use App\Models\Prestasi;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index(): View
    {
        $totalMahasiswa = User::query()
            ->where('role', 'mahasiswa')
            ->count();

        $totalPrestasi = Prestasi::query()
            ->where('status', Prestasi::STATUS_DIVERIFIKASI)
            ->count();

        $totalKategori = KategoriPrestasi::query()->count();

        $prestasiTerbaru = Prestasi::query()
            ->where('status', Prestasi::STATUS_DIVERIFIKASI)
            ->with([
                'mahasiswa',
                'mahasiswa.profilMahasiswa',
                'kategoriPrestasi',
            ])
            ->latest()
            ->limit(6)
            ->get();

        return view('public.home', [
            'totalMahasiswa' => $totalMahasiswa,
            'totalPrestasi' => $totalPrestasi,
            'totalKategori' => $totalKategori,
            'prestasiTerbaru' => $prestasiTerbaru,
        ]);
    }

    public function portfolio(Request $request): View
    {
        $search = $request->query('search');

        $mahasiswas = User::query()
            ->where('role', 'mahasiswa')
            ->with('profilMahasiswa')
            ->withCount([
                'prestasis as total_prestasi_terverifikasi' => function ($query) {
                    $query->where('status', Prestasi::STATUS_DIVERIFIKASI);
                },
            ])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhereHas('profilMahasiswa', function ($query) use ($search) {
                            $query->where('nim', 'like', "%{$search}%")
                                ->orWhere('program_studi', 'like', "%{$search}%")
                                ->orWhere('fakultas', 'like', "%{$search}%");
                        });
                });
            })
            ->having('total_prestasi_terverifikasi', '>', 0)
            ->orderByDesc('total_prestasi_terverifikasi')
            ->paginate(9)
            ->withQueryString();

        return view('public.portfolio', [
            'mahasiswas' => $mahasiswas,
            'search' => $search,
        ]);
    }
}