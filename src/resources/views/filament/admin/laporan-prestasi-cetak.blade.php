<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Prestasi Mahasiswa</title>

    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            background: #f3f4f6;
            color: #111827;
        }

        .actions {
            width: 297mm;
            margin: 20px auto;
            text-align: right;
        }

        .btn {
            border: 0;
            background: #111827;
            color: white;
            padding: 12px 18px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: bold;
        }

        .page {
            width: 297mm;
            min-height: 210mm;
            background: white;
            margin: 0 auto 24px;
            padding: 16mm;
        }

        .header {
            display: flex;
            justify-content: space-between;
            gap: 24px;
            border-bottom: 3px solid #111827;
            padding-bottom: 14px;
            margin-bottom: 18px;
        }

        h1 {
            margin: 0 0 8px;
            font-size: 24px;
        }

        .subtitle {
            margin: 0;
            color: #4b5563;
            font-size: 13px;
        }

        .filters {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 8px;
            margin-bottom: 18px;
        }

        .filter-box {
            border: 1px solid #d1d5db;
            padding: 8px;
            border-radius: 8px;
            font-size: 12px;
        }

        .filter-box span {
            display: block;
            color: #6b7280;
            margin-bottom: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        th {
            background: #111827;
            color: white;
            padding: 8px;
            text-align: left;
            border: 1px solid #111827;
        }

        td {
            padding: 8px;
            border: 1px solid #d1d5db;
            vertical-align: top;
        }

        .badge {
            display: inline-block;
            padding: 4px 7px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: bold;
            background: #fef3c7;
            color: #92400e;
        }

        .empty {
            text-align: center;
            padding: 20px;
            color: #6b7280;
        }

        @media print {
            body {
                background: white;
            }

            .actions {
                display: none;
            }

            .page {
                margin: 0;
                width: auto;
                min-height: auto;
                padding: 10mm;
            }
        }
    </style>
</head>
<body>
    <div class="actions">
        <button class="btn" onclick="window.print()">Cetak / Simpan PDF</button>
    </div>

    <main class="page">
        <section class="header">
            <div>
                <h1>Laporan Prestasi Mahasiswa</h1>
                <p class="subtitle">{{ config('app.name') }}</p>
            </div>

            <div>
                <p class="subtitle">Tanggal Cetak</p>
                <strong>{{ now()->format('d M Y H:i') }}</strong>
            </div>
        </section>

        <section class="filters">
            <div class="filter-box">
                <span>Status</span>
                <strong>{{ $filters['status'] ?? 'Semua' }}</strong>
            </div>

            <div class="filter-box">
                <span>Tingkat</span>
                <strong>{{ $filters['tingkat'] ?? 'Semua' }}</strong>
            </div>

            <div class="filter-box">
                <span>Kategori ID</span>
                <strong>{{ $filters['kategori_prestasi_id'] ?? 'Semua' }}</strong>
            </div>

            <div class="filter-box">
                <span>Tanggal Mulai</span>
                <strong>{{ $filters['tanggal_mulai'] ?? '-' }}</strong>
            </div>

            <div class="filter-box">
                <span>Tanggal Selesai</span>
                <strong>{{ $filters['tanggal_selesai'] ?? '-' }}</strong>
            </div>
        </section>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Mahasiswa</th>
                    <th>NIM</th>
                    <th>Program Studi</th>
                    <th>Judul Prestasi</th>
                    <th>Kategori</th>
                    <th>Tingkat</th>
                    <th>Peringkat</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Verifier</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($prestasis as $prestasi)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $prestasi->mahasiswa?->name ?? '-' }}</td>
                        <td>{{ $prestasi->mahasiswa?->profilMahasiswa?->nim ?? '-' }}</td>
                        <td>{{ $prestasi->mahasiswa?->profilMahasiswa?->program_studi ?? '-' }}</td>
                        <td>{{ $prestasi->judul }}</td>
                        <td>{{ $prestasi->kategoriPrestasi?->nama ?? '-' }}</td>
                        <td>{{ ucwords(str_replace('_', ' ', $prestasi->tingkat)) }}</td>
                        <td>{{ $prestasi->peringkat ?? '-' }}</td>
                        <td>{{ $prestasi->tanggal_prestasi ? $prestasi->tanggal_prestasi->format('d M Y') : '-' }}</td>
                        <td><span class="badge">{{ ucwords(str_replace('_', ' ', $prestasi->status)) }}</span></td>
                        <td>{{ $prestasi->verifier?->name ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="empty">
                            Data prestasi tidak ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </main>
</body>
</html>