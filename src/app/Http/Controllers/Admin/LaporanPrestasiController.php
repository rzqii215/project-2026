<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prestasi;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanPrestasiController extends Controller
{
    public function cetak(Request $request)
    {
        $this->authorizeAdmin();

        $prestasis = $this->query($request)->get();

        return view('admin.laporan-prestasi-cetak', [
            'prestasis' => $prestasis,
            'filters' => $request->only([
                'status',
                'tingkat',
                'kategori_prestasi_id',
                'tanggal_mulai',
                'tanggal_selesai',
            ]),
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $this->authorizeAdmin();

        $prestasis = $this->query($request)->get();

        $filename = 'laporan-prestasi-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($prestasis): void {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Mahasiswa',
                'NIM',
                'Program Studi',
                'Judul Prestasi',
                'Kategori',
                'Tingkat',
                'Penyelenggara',
                'Peringkat',
                'Tanggal Prestasi',
                'Status',
                'Diverifikasi Oleh',
                'Diverifikasi Pada',
            ]);

            foreach ($prestasis as $prestasi) {
                fputcsv($handle, [
                    $prestasi->mahasiswa?->name ?? '-',
                    $prestasi->mahasiswa?->profilMahasiswa?->nim ?? '-',
                    $prestasi->mahasiswa?->profilMahasiswa?->program_studi ?? '-',
                    $prestasi->judul,
                    $prestasi->kategoriPrestasi?->nama ?? '-',
                    $this->labelTingkat($prestasi->tingkat),
                    $prestasi->penyelenggara ?? '-',
                    $prestasi->peringkat ?? '-',
                    $this->formatTanggal($prestasi->tanggal_prestasi),
                    $this->labelStatus($prestasi->status),
                    $prestasi->verifier?->name ?? '-',
                    $this->formatTanggalJam($prestasi->diverifikasi_pada),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    private function query(Request $request): Builder
    {
        return Prestasi::query()
            ->with([
                'mahasiswa',
                'mahasiswa.profilMahasiswa',
                'kategoriPrestasi',
                'verifier',
            ])
            ->when($request->filled('status'), function (Builder $query) use ($request): Builder {
                return $query->where('status', $request->string('status')->toString());
            })
            ->when($request->filled('tingkat'), function (Builder $query) use ($request): Builder {
                return $query->where('tingkat', $request->string('tingkat')->toString());
            })
            ->when($request->filled('kategori_prestasi_id'), function (Builder $query) use ($request): Builder {
                return $query->where('kategori_prestasi_id', $request->integer('kategori_prestasi_id'));
            })
            ->when($request->filled('tanggal_mulai'), function (Builder $query) use ($request): Builder {
                return $query->whereDate('tanggal_prestasi', '>=', $request->date('tanggal_mulai'));
            })
            ->when($request->filled('tanggal_selesai'), function (Builder $query) use ($request): Builder {
                return $query->whereDate('tanggal_prestasi', '<=', $request->date('tanggal_selesai'));
            })
            ->orderByDesc('tanggal_prestasi')
            ->orderByDesc('created_at');
    }

    private function authorizeAdmin(): void
    {
        $user = Auth::user();

        abort_unless($user !== null, 403);

        $role = (string) data_get($user, 'role');

        $hasSystemRole = in_array($role, ['admin', 'owner'], true);

        $hasPermissionRole = method_exists($user, 'hasRole')
            && (
                $user->hasRole('super_admin')
                || $user->hasRole('admin')
            );

        abort_unless($hasSystemRole || $hasPermissionRole, 403);
    }

    private function labelStatus(?string $status): string
    {
        return match ($status) {
            Prestasi::STATUS_DIAJUKAN => 'Diajukan',
            Prestasi::STATUS_DIVERIFIKASI => 'Diverifikasi',
            Prestasi::STATUS_DITOLAK => 'Ditolak',
            default => '-',
        };
    }

    private function labelTingkat(?string $tingkat): string
    {
        return match ($tingkat) {
            Prestasi::TINGKAT_KAMPUS => 'Kampus',
            Prestasi::TINGKAT_KABUPATEN => 'Kabupaten/Kota',
            Prestasi::TINGKAT_PROVINSI => 'Provinsi',
            Prestasi::TINGKAT_NASIONAL => 'Nasional',
            Prestasi::TINGKAT_INTERNASIONAL => 'Internasional',
            default => '-',
        };
    }

    private function formatTanggal(mixed $tanggal): string
    {
        if (blank($tanggal)) {
            return '-';
        }

        return Carbon::parse($tanggal)->format('d-m-Y');
    }

    private function formatTanggalJam(mixed $tanggal): string
    {
        if (blank($tanggal)) {
            return '-';
        }

        return Carbon::parse($tanggal)->format('d-m-Y H:i');
    }
}